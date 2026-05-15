<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmotionalEvaluation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmotionalEvaluationController extends Controller
{
    public function index(Request $request): View
    {
        $emotion = $request->query('emotion', 'all');
        $emotion = is_string($emotion) ? $emotion : 'all';

        $query = EmotionalEvaluation::with('user')->orderByDesc('created_at');

        if ($emotion !== 'all') {
            $query->where('emotion', $emotion);
        }

        $evaluations = $query->paginate(20)->withQueryString();

        return view('admin.emotional-evaluations.index', [
            'evaluations' => $evaluations,
            'emotion' => $emotion,
        ]);
    }
}
