<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaregiverAssessmentController extends Controller
{
    public function index(Request $request): View
    {
        $latestAssessment = $request->user()?->caregiverAssessments()
            ->orderByDesc('created_at')
            ->first();

        return view('menu.assessment', [
            'latestAssessment' => $latestAssessment,
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
