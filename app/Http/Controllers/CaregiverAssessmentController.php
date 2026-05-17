<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaregiverAssessmentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $latestAssessment = $user?->caregiverAssessments()
            ->orderByDesc('created_at')
            ->first();

        $ecogHistory = $user?->ecogAssessments()
            ->orderByDesc('created_at')
            ->take(5)
            ->get() ?? collect();

        $latestEcog = $ecogHistory->first();

        $esasHistory = $user?->esasAssessments()
            ->orderByDesc('created_at')
            ->take(5)
            ->get() ?? collect();

        $latestEsas = $esasHistory->first();

        $swbsHistory = $user?->swbsAssessments()
            ->orderByDesc('created_at')
            ->take(5)
            ->get() ?? collect();

        $latestSwbs = $swbsHistory->first();

        $assessmentHistory = collect()
            ->merge($swbsHistory->map(function ($entry) {
                return [
                    'date' => $entry->created_at,
                    'instrument' => 'SWBS',
                    'result' => 'Total ' . $entry->total_score,
                ];
            }))
            ->merge($ecogHistory->map(function ($entry) {
                return [
                    'date' => $entry->created_at,
                    'instrument' => 'ECOG',
                    'result' => $entry->score_label,
                ];
            }))
            ->merge($esasHistory->map(function ($entry) {
                return [
                    'date' => $entry->created_at,
                    'instrument' => 'ESAS',
                    'result' => 'Total ' . $entry->total_score,
                ];
            }))
            ->sortByDesc('date')
            ->take(8)
            ->values();
        return view('menu.assessment', [
            'latestAssessment' => $latestAssessment,
            'ecogHistory' => $ecogHistory,
            'latestEcog' => $latestEcog,
            'esasHistory' => $esasHistory,
            'latestEsas' => $latestEsas,
            'swbsHistory' => $swbsHistory,
            'latestSwbs' => $latestSwbs,
            'assessmentHistory' => $assessmentHistory,
            'userInitials' => $user ? strtoupper(substr($user->name, 0, 2)) : '',
            'userAge' => $user?->patient_age,
            'userGender' => $user?->patient_gender,
            'userMaritalStatus' => $user?->marital_status,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'anxiety_level' => ['required', 'integer', 'between:1,5'],
            'grief_level' => ['required', 'integer', 'between:1,5'],
            'communication_level' => ['required', 'integer', 'between:1,5'],
        ]);

        $request->user()?->caregiverAssessments()->create($validated);

        return back()->with('status', 'assessment-saved');
    }
}
