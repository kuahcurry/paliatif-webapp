<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EcogAssessmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'respondent_initials' => ['nullable', 'string', 'max:20'],
            'age' => ['nullable', 'integer', 'min:0', 'max:130'],
            'gender' => ['nullable', 'string', 'max:20'],
            'marital_status' => ['nullable', 'string', 'max:30'],
            'cancer_stage' => ['nullable', 'string', 'max:30'],
            'score' => ['required', 'integer', 'between:0,5'],
        ]);

        $score = (int) $validated['score'];
        $scoreLabel = $this->labelForScore($score);

        $request->user()?->ecogAssessments()->create([
            'respondent_initials' => $validated['respondent_initials'] ?? null,
            'age' => $validated['age'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'marital_status' => $validated['marital_status'] ?? null,
            'cancer_stage' => $validated['cancer_stage'] ?? null,
            'score' => $score,
            'score_label' => $scoreLabel,
        ]);

        return back()->with('status', 'ecog-saved');
    }

    private function labelForScore(int $score): string
    {
        if ($score <= 1) {
            return 'Sangat Baik';
        }

        if ($score <= 3) {
            return 'Cukup Baik';
        }

        return 'Kurang Baik';
    }
}
