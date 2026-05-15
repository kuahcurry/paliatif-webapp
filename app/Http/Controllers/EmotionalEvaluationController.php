<?php

namespace App\Http\Controllers;

use App\Models\EmotionalEvaluation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmotionalEvaluationController extends Controller
{
    private const MAX_SESSIONS = 5;

    public function index(Request $request): View
    {
        $user = $request->user();

        $evaluations = $user->emotionalEvaluations()
            ->orderBy('session_number')
            ->get();

        $sessionCount = $evaluations->count();
        $currentSessionNumber = $sessionCount + 1;
        $hasReachedMax = $sessionCount >= self::MAX_SESSIONS;

        $lastService = null;
        $lastServiceDate = null;
        $lastEvaluation = $evaluations->last();
        if ($lastEvaluation) {
            $lastService = $this->getEmotionLabel($lastEvaluation->emotion);
            $lastServiceDate = $lastEvaluation->created_at->format('d M Y, H:i') . ' WIB';
        }

        $historyItems = [];
        for ($i = 1; $i <= self::MAX_SESSIONS; $i++) {
            $eval = $evaluations->firstWhere('session_number', $i);
            if ($eval) {
                $historyItems[] = [
                    'label' => $this->getEmotionLabel($eval->emotion),
                    'date' => $eval->created_at->format('d M Y, H:i') . ' WIB',
                    'status' => 'Sesi ' . $i,
                    'tone' => $this->getEmotionTone($eval->emotion),
                ];
            } else {
                $historyItems[] = [
                    'label' => 'Belum dievaluasi',
                    'date' => '-',
                    'status' => 'Sesi ' . $i,
                    'tone' => 'neutral',
                ];
            }
        }

        return view('menu.emotional-evaluation', [
            'patientName' => $user->name,
            'patientAge' => $user->patient_age ? $user->patient_age . ' Tahun' : '--',
            'patientGender' => $user->patient_gender ?? '--',
            'patientRm' => $user->patient_rm ? 'No. RM: ' . $user->patient_rm : '--',
            'patientRoom' => $user->patient_room ? 'Ruang: ' . $user->patient_room : '--',
            'nurseName' => $user->name,
            'nurseRole' => $user->is_admin ? 'Admin' : 'Pasien',
            'sessionCount' => $sessionCount,
            'currentSessionNumber' => $currentSessionNumber,
            'maxSessions' => self::MAX_SESSIONS,
            'hasReachedMax' => $hasReachedMax,
            'lastService' => $lastService ?? 'Belum ada',
            'lastServiceDate' => $lastServiceDate ?? '-',
            'historyItems' => $historyItems,
            'lastEmotion' => old('emotion'),
            'lastNote' => old('note'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $sessionCount = $user->emotionalEvaluations()->count();

        if ($sessionCount >= self::MAX_SESSIONS) {
            return back()->withErrors(['session' => 'Anda telah mencapai batas maksimal 5 sesi evaluasi.']);
        }

        $validated = $request->validate([
            'emotion' => ['required', 'string', 'in:very_calm,calm,neutral,anxious_sad,distressed'],
            'note' => ['nullable', 'string', 'max:300'],
        ]);

        $validated['user_id'] = $user->id;
        $validated['session_number'] = $sessionCount + 1;

        EmotionalEvaluation::create($validated);

        return redirect()->route('menu.emotional-evaluation')
            ->with('status', 'evaluation-saved');
    }

    private function getEmotionLabel(string $emotion): string
    {
        return match ($emotion) {
            'very_calm' => 'Sangat Tenang',
            'calm' => 'Tenang',
            'neutral' => 'Biasa Saja',
            'anxious_sad' => 'Cemas / Sedih',
            'distressed' => 'Sangat Tertekan',
            default => 'Tidak diketahui',
        };
    }

    private function getEmotionTone(string $emotion): string
    {
        return match ($emotion) {
            'very_calm' => 'good',
            'calm' => 'calm',
            'neutral' => 'neutral',
            'anxious_sad', 'distressed' => 'neutral',
            default => 'neutral',
        };
    }
}
