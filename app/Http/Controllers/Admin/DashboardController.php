<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EducationModule;
use App\Models\EmotionalEvaluation;
use App\Models\JournalEntry;
use App\Models\Prayer;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_prayers' => Prayer::count(),
            'total_journals' => JournalEntry::count(),
            'total_modules' => EducationModule::count(),
            'total_evaluations' => EmotionalEvaluation::count(),
            'recent_users' => User::latest()->limit(5)->get(),
            'recent_evaluations' => EmotionalEvaluation::with('user')->latest()->limit(5)->get(),
        ];

        return view('admin.dashboard.index', $stats);
    }
}
