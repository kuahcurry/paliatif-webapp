<?php

namespace App\Http\Controllers;

use App\Models\Prayer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PrayerController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');
        $sort = $request->query('sort', 'latest');

        $query = Prayer::query()->where('is_public', true);

        if (in_array($category, ['self', 'others', 'gratitude'], true)) {
            $query->where('category', $category);
        } else {
            $category = 'all';
        }

        if ($sort === 'support') {
            $query->orderByDesc('support_count');
        } else {
            $sort = 'latest';
            $query->orderByDesc('created_at');
        }

        $prayers = $query->limit(36)->get();

        return view('prayers.index', [
            'prayers' => $prayers,
            'category' => $category,
            'sort' => $sort,
            'recaptchaSiteKey' => config('services.recaptcha.site_key'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $recaptchaEnabled = $this->recaptchaEnabled();

        $rules = [
            'display_name' => ['nullable', 'string', 'max:60'],
            'category' => ['required', Rule::in(['self', 'others', 'gratitude'])],
            'content' => ['required', 'string', 'max:500'],
            'is_public' => ['required', 'boolean'],
        ];

        if ($recaptchaEnabled) {
            $rules['recaptcha_token'] = ['required', 'string'];
        }

        $validated = $request->validate($rules);

        if ($recaptchaEnabled && ! $this->verifyRecaptcha($validated['recaptcha_token'])) {
            return back()
                ->withErrors(['recaptcha' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.'])
                ->withInput();
        }

        Prayer::create([
            'user_id' => $request->user()?->id,
            'category' => $validated['category'],
            'is_public' => (bool) $validated['is_public'],
            'display_name' => $validated['display_name'] ?: null,
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('prayers.index')
            ->with('status', $validated['is_public'] ? 'prayer-public' : 'prayer-private');
    }

    public function support(Request $request, Prayer $prayer): RedirectResponse
    {
        if (! $prayer->is_public) {
            abort(404);
        }

        $prayer->increment('support_count');

        return back()->with('status', 'support-added');
    }

    private function recaptchaEnabled(): bool
    {
        return (bool) config('services.recaptcha.site_key')
            && (bool) config('services.recaptcha.secret_key');
    }

    private function verifyRecaptcha(string $token): bool
    {
        $secret = config('services.recaptcha.secret_key');
        $threshold = (float) config('services.recaptcha.score_threshold', 0.5);

        if (! $secret) {
            return false;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $token,
        ]);

        if (! $response->ok()) {
            return false;
        }

        $payload = $response->json();

        return ($payload['success'] ?? false)
            && ($payload['score'] ?? 0) >= $threshold
            && ($payload['action'] ?? '') === 'prayer';
    }
}
