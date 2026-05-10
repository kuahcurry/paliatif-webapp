<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today();

        $entries = $user->journalEntries()
            ->orderByDesc('entry_date')
            ->paginate(10);

        $hasToday = $user->journalEntries()
            ->whereDate('entry_date', $today)
            ->exists();

        return view('journals.index', [
            'entries' => $entries,
            'hasToday' => $hasToday,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
            'is_shareable' => ['nullable', 'boolean'],
        ]);

        $alreadyFilled = $user->journalEntries()
            ->whereDate('entry_date', $today)
            ->exists();

        if ($alreadyFilled) {
            return back()
                ->withErrors(['journal' => 'Catatan hari ini sudah dibuat.'])
                ->withInput();
        }

        $user->journalEntries()->create([
            'entry_date' => $today,
            'content' => $validated['content'],
            'is_shareable' => (bool) ($validated['is_shareable'] ?? false),
        ]);

        return back()->with('status', 'journal-saved');
    }
}
