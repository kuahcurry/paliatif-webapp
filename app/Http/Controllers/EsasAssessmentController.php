<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EsasAssessmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pain' => ['required', 'integer', 'between:0,10'],
            'fatigue' => ['required', 'integer', 'between:0,10'],
            'nausea' => ['required', 'integer', 'between:0,10'],
            'stress' => ['required', 'integer', 'between:0,10'],
            'anxiety' => ['required', 'integer', 'between:0,10'],
            'drowsiness' => ['required', 'integer', 'between:0,10'],
            'appetite' => ['required', 'integer', 'between:0,10'],
            'wellbeing' => ['required', 'integer', 'between:0,10'],
            'shortness_of_breath' => ['required', 'integer', 'between:0,10'],
            'other_problem' => ['required', 'integer', 'between:0,10'],
        ]);

        $totalScore = array_sum($validated);

        $request->user()?->esasAssessments()->create(array_merge($validated, [
            'total_score' => $totalScore,
        ]));

        return back()->with('status', 'esas-saved');
    }
}
