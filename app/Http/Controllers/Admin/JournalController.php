<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(Request $request): View
    {
        $visibility = $request->query('visibility', 'shareable');
        $visibility = is_string($visibility) ? $visibility : 'shareable';

        $query = JournalEntry::query()->with('user')->orderByDesc('entry_date');

        if ($visibility === 'all') {
            // no filter
        } else {
            $visibility = 'shareable';
            $query->where('is_shareable', true);
        }

        $entries = $query->paginate(15)->withQueryString();

        return view('admin.journals.index', [
            'entries' => $entries,
            'visibility' => $visibility,
        ]);
    }

    public function update(Request $request, JournalEntry $journalEntry): RedirectResponse
    {
        if (! $journalEntry->is_shareable) {
            abort(403);
        }

        $validated = $request->validate([
            'provider_response' => ['nullable', 'string', 'max:1000'],
        ]);

        $journalEntry->provider_response = $validated['provider_response'] ?? null;
        $journalEntry->save();

        return back()->with('status', 'journal-responded');
    }
}
