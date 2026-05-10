<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prayer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrayerController extends Controller
{
    public function index(Request $request): View
    {
        $visibility = $request->query('visibility', 'all');
        $category = $request->query('category', 'all');
        $search = trim((string) $request->query('q', ''));

        $query = Prayer::query()->with('user')->orderByDesc('created_at');

        if ($visibility === 'public') {
            $query->where('is_public', true);
        } elseif ($visibility === 'private') {
            $query->where('is_public', false);
        } else {
            $visibility = 'all';
        }

        if (in_array($category, ['self', 'others', 'gratitude'], true)) {
            $query->where('category', $category);
        } else {
            $category = 'all';
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('content', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%");
            });
        }

        $prayers = $query->paginate(15)->withQueryString();

        return view('admin.prayers.index', [
            'prayers' => $prayers,
            'visibility' => $visibility,
            'category' => $category,
            'search' => $search,
        ]);
    }

    public function destroy(Prayer $prayer): RedirectResponse
    {
        $prayer->delete();

        return redirect()
            ->route('admin.prayers.index')
            ->with('status', 'prayer-deleted');
    }
}
