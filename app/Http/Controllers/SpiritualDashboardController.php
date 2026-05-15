<?php

namespace App\Http\Controllers;

use App\Models\EducationModule;
use App\Models\SpiritualRadarLog;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SpiritualDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $range = (int) $request->query('range', 7);
        if (! in_array($range, [7, 30], true)) {
            $range = 7;
        }

        $today = Carbon::today();
        $startDate = $today->copy()->subDays($range - 1);

        $logs = $user->spiritualRadarLogs()
            ->whereDate('date', '>=', $startDate)
            ->orderBy('date')
            ->get();

        $allLogs = $user->spiritualRadarLogs()->orderByDesc('date')->get();
        $latestLog = $allLogs->first();
        $hasToday = $latestLog?->date?->isSameDay($today) ?? false;

        $dailyTip = EducationModule::where('is_active', true)
            ->inRandomOrder()
            ->first();

        $recommendedActivities = [
            'doa' => EducationModule::where('is_active', true)->whereJsonContains('tags', 'spiritual_support')->inRandomOrder()->first(),
            'dzikir' => EducationModule::where('is_active', true)->whereJsonContains('tags', 'coping')->inRandomOrder()->first(),
            'refleksi' => EducationModule::where('is_active', true)->whereJsonContains('tags', 'grief')->inRandomOrder()->first(),
            'istirahat' => EducationModule::where('is_active', true)->inRandomOrder()->first(),
        ];

        $overallStatus = $this->buildOverallStatus($latestLog);
        $spiritualScoreToday = $latestLog ? $this->calculateSpiritualScore($latestLog) : null;
        $emotionScoreToday = $latestLog ? $this->calculateEmotionScore($latestLog) : null;
        $streak = $this->calculateStreak($allLogs);
        $activityHighlight = $this->buildActivityHighlight($latestLog);

        $chartLabels = $logs->map(fn (SpiritualRadarLog $log) => $log->date->format('d M'))->all();
        $scoreTrend = $logs->map(fn (SpiritualRadarLog $log) => $this->calculateSpiritualScore($log))->all();
        $emotionTrend = $logs->map(fn (SpiritualRadarLog $log) => $this->calculateEmotionScore($log))->all();

        $esasScores = $this->buildEsasScores($latestLog);
        $todayLabel = $this->formatDayName($today);

        return view('dashboard', [
            'range' => $range,
            'hasToday' => $hasToday,
            'latestLog' => $latestLog,
            'summaryText' => $this->buildSummaryText($latestLog),
            'trendText' => $this->buildTrendText($logs, $range),
            'recommendations' => $this->buildRecommendations($latestLog),
            'dailyTip' => $dailyTip,
            'recommendedActivities' => $recommendedActivities,
            'overallStatus' => $overallStatus,
            'spiritualScoreToday' => $spiritualScoreToday,
            'emotionScoreToday' => $emotionScoreToday,
            'streak' => $streak,
            'activityHighlight' => $activityHighlight,
            'chartLabels' => $chartLabels,
            'scoreTrend' => $scoreTrend,
            'emotionTrend' => $emotionTrend,
            'esasScores' => $esasScores,
            'todayDate' => $today->format('d M Y'),
            'todayDayName' => $todayLabel,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $today = Carbon::today();

        $request->validate([
            'score_meaning' => ['required', 'integer', 'between:1,5'],
            'score_closeness' => ['required', 'integer', 'between:1,5'],
            'score_peace' => ['required', 'integer', 'between:1,5'],
            'score_fear' => ['required', 'integer', 'between:1,5'],
            'score_loneliness' => ['required', 'integer', 'between:1,5'],
            'symptoms' => ['nullable', 'string', 'max:500'],
            'symptom_pain' => ['required', 'integer', 'between:1,5'],
            'symptom_fatigue' => ['required', 'integer', 'between:1,5'],
            'symptom_nausea' => ['required', 'integer', 'between:1,5'],
            'symptom_anxiety' => ['required', 'integer', 'between:1,5'],
            'symptom_sadness' => ['required', 'integer', 'between:1,5'],
        ]);

        $alreadyFilled = $user->spiritualRadarLogs()
            ->whereDate('date', $today)
            ->exists();

        if ($alreadyFilled) {
            return back()
                ->withErrors(['daily' => 'Anda sudah mengisi cek harian hari ini.'])
                ->withInput();
        }

        $user->spiritualRadarLogs()->create([
            'date' => $today,
            'score_meaning' => (int) $request->input('score_meaning'),
            'score_closeness' => (int) $request->input('score_closeness'),
            'score_peace' => (int) $request->input('score_peace'),
            'score_fear' => (int) $request->input('score_fear'),
            'score_loneliness' => (int) $request->input('score_loneliness'),
            'symptoms' => $request->input('symptoms'),
            'symptom_pain' => (int) $request->input('symptom_pain'),
            'symptom_fatigue' => (int) $request->input('symptom_fatigue'),
            'symptom_nausea' => (int) $request->input('symptom_nausea'),
            'symptom_anxiety' => (int) $request->input('symptom_anxiety'),
            'symptom_sadness' => (int) $request->input('symptom_sadness'),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('status', 'radar-saved');
    }

    private function scoreLabels(): array
    {
        return [
            'score_meaning' => 'Makna hidup',
            'score_closeness' => 'Kedekatan dengan Tuhan/yang Ilahi',
            'score_peace' => 'Rasa damai',
            'score_fear' => 'Rasa takut',
            'score_loneliness' => 'Rasa kesepian',
        ];
    }

    private function buildSummaryText(?SpiritualRadarLog $latestLog): ?string
    {
        if (! $latestLog) {
            return null;
        }

        $scores = [
            'score_meaning' => $latestLog->score_meaning,
            'score_closeness' => $latestLog->score_closeness,
            'score_peace' => $latestLog->score_peace,
            'score_fear' => $latestLog->score_fear,
            'score_loneliness' => $latestLog->score_loneliness,
        ];

        $labels = $this->scoreLabels();
        $lowestValue = min($scores);
        $lowestKey = array_search($lowestValue, $scores, true);

        return "Skor terendah hari ini: {$labels[$lowestKey]} ({$lowestValue}/5).";
    }

    private function buildTrendText(Collection $logs, int $range): ?string
    {
        if ($logs->count() < 2) {
            return null;
        }

        $first = $logs->first();
        $last = $logs->last();

        $logs = $logs->values();

        $diffs = [
            'Makna hidup' => $last->score_meaning - $first->score_meaning,
            'Kedekatan dengan Tuhan/yang Ilahi' => $last->score_closeness - $first->score_closeness,
            'Rasa damai' => $last->score_peace - $first->score_peace,
            'Rasa takut' => $last->score_fear - $first->score_fear,
            'Rasa kesepian' => $last->score_loneliness - $first->score_loneliness,
        ];

        $minDiff = min($diffs);
        $maxDiff = max($diffs);
        $minLabel = array_search($minDiff, $diffs, true);
        $maxLabel = array_search($maxDiff, $diffs, true);

        if ($minDiff <= -1) {
            return "Dalam {$range} hari terakhir, {$minLabel} cenderung menurun.";
        }

        if ($maxDiff >= 1) {
            return "Dalam {$range} hari terakhir, {$maxLabel} cenderung meningkat.";
        }

        return "Dalam {$range} hari terakhir, kondisi relatif stabil.";
    }

    private function buildRecommendations(?SpiritualRadarLog $latestLog): array
    {
        if (! $latestLog) {
            return [];
        }

        $recommendations = [];

        if ($latestLog->score_peace <= 2) {
            $recommendations[] = 'Cobalah doa atau meditasi napas 2-3 menit untuk menenangkan diri.';
        }

        if ($latestLog->score_fear <= 2) {
            $recommendations[] = 'Bagikan kekhawatiran Anda kepada keluarga atau pendamping.';
        }

        if ($latestLog->score_loneliness <= 2) {
            $recommendations[] = 'Hubungi keluarga atau teman dekat untuk berbagi kabar.';
        }

        if ($latestLog->score_meaning <= 2) {
            $recommendations[] = 'Tulis 1 hal kecil yang membuat hidup terasa bermakna hari ini.';
        }

        if ($latestLog->score_closeness <= 2) {
            $recommendations[] = 'Luangkan waktu singkat untuk doa, dzikir, atau refleksi.';
        }

        if (empty($recommendations)) {
            $recommendations[] = 'Pertahankan rutinitas kecil yang membantu Anda merasa lebih tenang.';
        }

        return array_slice($recommendations, 0, 3);
    }

    private function calculateSpiritualScore(SpiritualRadarLog $log): int
    {
        $average = (
            $log->score_meaning
            + $log->score_closeness
            + $log->score_peace
            + $log->score_fear
            + $log->score_loneliness
        ) / 5;

        return (int) round($average * 20);
    }

    private function calculateEmotionScore(SpiritualRadarLog $log): float
    {
        $score = (
            $log->score_peace
            + (6 - $log->score_fear)
            + (6 - $log->score_loneliness)
        ) / 3;

        return round($score, 1);
    }

    private function buildOverallStatus(?SpiritualRadarLog $log): array
    {
        if (! $log) {
            return [
                'label' => 'Belum ada data',
                'tone' => 'text-slate-500 bg-slate-100',
            ];
        }

        $average = (
            $log->score_meaning
            + $log->score_closeness
            + $log->score_peace
            + $log->score_fear
            + $log->score_loneliness
        ) / 5;

        if ($average >= 4) {
            return [
                'label' => 'Stabil',
                'tone' => 'text-emerald-700 bg-emerald-50',
            ];
        }

        if ($average >= 3) {
            return [
                'label' => 'Cukup stabil',
                'tone' => 'text-amber-700 bg-amber-50',
            ];
        }

        return [
            'label' => 'Perlu dukungan',
            'tone' => 'text-rose-700 bg-rose-50',
        ];
    }

    private function calculateStreak(Collection $logs): int
    {
        if ($logs->isEmpty()) {
            return 0;
        }

        $streak = 1;
        $logs = $logs->values();

        for ($index = 1; $index < $logs->count(); $index++) {
            $previous = $logs[$index - 1]->date->copy()->subDay();
            if (! $previous->isSameDay($logs[$index]->date)) {
                break;
            }
            $streak++;
        }

        return $streak;
    }

    private function buildActivityHighlight(?SpiritualRadarLog $log): string
    {
        if (! $log) {
            return 'Mulai dari doa singkat dan refleksi ringan.';
        }

        if ($log->score_peace <= 2) {
            return 'Dzikir pagi, refleksi napas.';
        }

        if ($log->score_fear <= 2) {
            return 'Refleksi diri, doa penguatan.';
        }

        if ($log->score_loneliness <= 2) {
            return 'Doa bersama keluarga, kontak hangat.';
        }

        return 'Dzikir pagi, refleksi, syukur harian.';
    }

    private function buildEsasScores(?SpiritualRadarLog $log): array
    {
        return [
            ['label' => 'Nyeri', 'value' => $log?->symptom_pain],
            ['label' => 'Lelah', 'value' => $log?->symptom_fatigue],
            ['label' => 'Mual', 'value' => $log?->symptom_nausea],
            ['label' => 'Cemas', 'value' => $log?->symptom_anxiety],
            ['label' => 'Sedih', 'value' => $log?->symptom_sadness],
        ];
    }

    private function formatDayName(Carbon $date): string
    {
        $map = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        return $map[$date->format('l')] ?? $date->format('l');
    }
}
