<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(6);

        $entryTypes = [
            'patient' => 'Jurnal Pasien',
            'family' => 'Jurnal Keluarga',
        ];

        $categories = [
            'Perasaan',
            'Harapan',
            'Doa',
            'Syukur',
            'Kekhawatiran',
            'Lainnya',
        ];

        $activeEntryType = (string) $request->query('entry_type', 'patient');
        if (! array_key_exists($activeEntryType, $entryTypes)) {
            $activeEntryType = 'patient';
        }

        $activeCategory = (string) $request->query('category', 'all');
        if ($activeCategory !== 'all' && ! in_array($activeCategory, $categories, true)) {
            $activeCategory = 'all';
        }

        $entriesQuery = $user->journalEntries()
            ->where('entry_type', $activeEntryType)
            ->whereDate('entry_date', '>=', $startDate)
            ->orderByDesc('entry_date')
            ->orderByDesc('created_at');

        if ($activeCategory !== 'all') {
            if ($activeCategory === 'Lainnya') {
                $entriesQuery->where(function ($query) {
                    $query->whereNull('category')
                        ->orWhere('category', 'Lainnya');
                });
            } else {
                $entriesQuery->where('category', $activeCategory);
            }
        }

        $entries = $entriesQuery->paginate(7)->withQueryString();

        $hasToday = $user->journalEntries()
            ->whereDate('entry_date', $today)
            ->where('entry_type', $activeEntryType)
            ->exists();

        $summaryCounts = $user->journalEntries()
            ->select('category', DB::raw('count(*) as total'))
            ->where('entry_type', $activeEntryType)
            ->whereDate('entry_date', '>=', $startDate)
            ->groupBy('category')
            ->pluck('total', 'category');

        $summaryTones = [
            'Syukur' => 'good',
            'Harapan' => 'calm',
            'Doa' => 'focus',
            'Perasaan' => 'neutral',
            'Kekhawatiran' => 'warn',
            'Lainnya' => 'neutral',
        ];

        $summaryItems = [];
        foreach ($categories as $category) {
            $count = 0;
            if ($category === 'Lainnya') {
                $count += (int) ($summaryCounts[null] ?? 0);
                $count += (int) ($summaryCounts['Lainnya'] ?? 0);
            } else {
                $count = (int) ($summaryCounts[$category] ?? 0);
            }

            $summaryItems[] = [
                'label' => $category,
                'count' => $count,
                'tone' => $summaryTones[$category] ?? 'neutral',
            ];
        }

        return view('journals.index', [
            'entries' => $entries,
            'hasToday' => $hasToday,
            'writeCategories' => $categories,
            'historyCategories' => $categories,
            'summaryItems' => $summaryItems,
            'entryTypes' => $entryTypes,
            'activeEntryType' => $activeEntryType,
            'activeCategory' => $activeCategory,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        $validated = $request->validate([
            'entry_type' => ['required', Rule::in(['patient', 'family'])],
            'category' => ['nullable', Rule::in(['Perasaan', 'Harapan', 'Doa', 'Syukur', 'Kekhawatiran', 'Lainnya'])],
            'content' => ['required', 'string', 'max:1000'],
            'is_shareable' => ['nullable', 'boolean'],
        ]);

        $entryType = $validated['entry_type'];
        $category = $validated['category'] ?? null;
        if (is_string($category) && $category === '') {
            $category = null;
        }

        $alreadyFilled = $user->journalEntries()
            ->whereDate('entry_date', $today)
            ->where('entry_type', $entryType)
            ->exists();

        if ($alreadyFilled) {
            return back()
                ->withErrors(['journal' => 'Catatan hari ini sudah dibuat.'])
                ->withInput();
        }

        $user->journalEntries()->create([
            'entry_date' => $today,
            'entry_type' => $entryType,
            'category' => $category,
            'content' => $validated['content'],
            'is_shareable' => (bool) ($validated['is_shareable'] ?? false),
        ]);

        return back()->with('status', 'journal-saved');
    }
}
