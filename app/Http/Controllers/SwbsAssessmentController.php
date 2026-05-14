<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SwbsAssessmentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'q1' => ['required', 'integer', 'between:1,6'],
            'q2' => ['required', 'integer', 'between:1,6'],
            'q3' => ['required', 'integer', 'between:1,6'],
            'q4' => ['required', 'integer', 'between:1,6'],
            'q5' => ['required', 'integer', 'between:1,6'],
            'q6' => ['required', 'integer', 'between:1,6'],
            'q7' => ['required', 'integer', 'between:1,6'],
            'q8' => ['required', 'integer', 'between:1,6'],
            'q9' => ['required', 'integer', 'between:1,6'],
            'q10' => ['required', 'integer', 'between:1,6'],
            'q11' => ['required', 'integer', 'between:1,6'],
            'q12' => ['required', 'integer', 'between:1,6'],
            'q13' => ['required', 'integer', 'between:1,6'],
            'q14' => ['required', 'integer', 'between:1,6'],
            'q15' => ['required', 'integer', 'between:1,6'],
            'q16' => ['required', 'integer', 'between:1,6'],
            'q17' => ['required', 'integer', 'between:1,6'],
            'q18' => ['required', 'integer', 'between:1,6'],
            'q19' => ['required', 'integer', 'between:1,6'],
            'q20' => ['required', 'integer', 'between:1,6'],
        ]);

        $totalScore = array_sum($validated);

        $request->user()?->swbsAssessments()->create(array_merge($validated, [
            'total_score' => $totalScore,
        ]));

        return back()->with('status', 'swbs-saved');
    }
}
