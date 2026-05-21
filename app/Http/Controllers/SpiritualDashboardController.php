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

        $dailyTip = $this->getDailyTrivia($user->religion);

        $historicalLogs = $user->spiritualRadarLogs()
            ->orderBy('date')
            ->get();

        $historicalData = $historicalLogs->map(fn (SpiritualRadarLog $log) => [
            'date' => $log->date->format('Y-m-d'),
            'label' => $log->date->format('d M'),
            'spiritual' => $this->calculateSpiritualScore($log),
            'emotion' => $this->calculateEmotionScore($log),
        ])->all();

        $highlightedModules = EducationModule::where('is_active', true)
            ->where('is_highlighted', true)
            ->take(4)
            ->get();

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

        $todayJournals = $user->journalEntries()->whereDate('created_at', $today)->count();
        $todayEvaluations = $user->emotionalEvaluations()->whereDate('created_at', $today)->count();
        $totalPrayers = $user->prayers()->count();
        $intervention = $user->spiritualIntervention()->first();
        $treeStage = $intervention?->current_step ?? 1;
        $totalActivities = $intervention?->progress_points ?? 0;
        $hasSwbs = $user->swbsAssessments()->exists();
        $hasEcog = $user->ecogAssessments()->exists();
        $hasEsas = $user->esasAssessments()->exists();

        return view('dashboard', [
            'range' => $range,
            'hasToday' => $hasToday,
            'latestLog' => $latestLog,
            'summaryText' => $this->buildSummaryText($latestLog),
            'trendText' => $this->buildTrendText($logs, $range),
            'recommendations' => $this->buildRecommendations($latestLog),
            'dailyTip' => $dailyTip,
            'highlightedModules' => $highlightedModules,
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
            'todayJournals' => $todayJournals,
            'todayEvaluations' => $todayEvaluations,
            'totalPrayers' => $totalPrayers,
            'treeStage' => $treeStage,
            'totalActivities' => $totalActivities,
            'hasSwbs' => $hasSwbs,
            'hasEcog' => $hasEcog,
            'hasEsas' => $hasEsas,
            'historicalData' => $historicalData,
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
        $user = request()->user();
        $esas = $user?->esasAssessments()->latest()->first();

        if (! $esas) {
            return [
                ['label' => 'Nyeri', 'value' => null],
                ['label' => 'Lelah', 'value' => null],
                ['label' => 'Mual', 'value' => null],
                ['label' => 'Cemas', 'value' => null],
                ['label' => 'Mengantuk', 'value' => null],
                ['label' => 'Nafsu Makan', 'value' => null],
            ];
        }

        return [
            ['label' => 'Nyeri', 'value' => $esas->pain],
            ['label' => 'Lelah', 'value' => $esas->fatigue],
            ['label' => 'Mual', 'value' => $esas->nausea],
            ['label' => 'Cemas', 'value' => $esas->anxiety],
            ['label' => 'Mengantuk', 'value' => $esas->drowsiness],
            ['label' => 'Nafsu Makan', 'value' => $esas->appetite],
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

    private function getDailyTrivia(?string $religion): array
    {
        $islamTips = [
            ['text' => 'Ingatlah, hanya dengan mengingat Allah hati menjadi tenteram.', 'source' => 'QS. Ar-Rad: 28'],
            ['text' => 'Allah tidak membebani seseorang melainkan sesuai dengan kesanggupannya.', 'source' => 'QS. Al-Baqarah: 286'],
            ['text' => 'Sesungguhnya sesudah kesulitan itu ada kemudahan.', 'source' => 'QS. Al-Insyirah: 6'],
            ['text' => 'Doa adalah senjata bagi orang mukmin dan tiang agama.', 'source' => 'Hadits Riwayat Al-Hakim'],
            ['text' => 'Sabar dan shalatlah sebagai penolongmu. Sesungguhnya yang demikian itu sungguh berat, kecuali bagi orang-orang yang khusyu\'.', 'source' => 'QS. Al-Baqarah: 45'],
            ['text' => 'Membaca Al-Qur\'an dan berdzikir dapat memberikan ketenangan batin yang mendalam bagi jiwa yang lelah.', 'source' => 'Panduan Spiritual Ruang Hening'],
        ];

        $kristenTips = [
            ['text' => 'Janganlah hendaknya kamu kuatir tentang apa pun juga, tetapi nyatakanlah dalam segala hal keinginanmu kepada Allah dalam doa.', 'source' => 'Filipi 4:6'],
            ['text' => 'Tuhan adalah gembalaku, takkan kekurangan aku. Ia membaringkan aku di padang yang berumput hijau.', 'source' => 'Mazmur 23:1-2'],
            ['text' => 'Damai sejahtera Kutinggalkan bagimu. Damai sejahtera-Ku Kuberikan kepadamu.', 'source' => 'Yohanes 14:27'],
            ['text' => 'Serahkanlah segala kekuatiranmu kepada-Nya, sebab Ia yang memelihara kamu.', 'source' => '1 Petrus 5:7'],
            ['text' => 'Sebab Aku ini mengetahui rancangan-rancangan apa yang ada pada-Ku mengenai kamu, yaitu rancangan damai sejahtera.', 'source' => 'Yeremia 29:11'],
            ['text' => 'Tetapi orang-orang yang menanti-nantikan TUHAN mendapat kekuatan baru: mereka seumpama rajawali yang naik terbang dengan kekuatan sayapnya.', 'source' => 'Yesaya 40:31'],
        ];

        $hinduTips = [
            ['text' => 'Pikiran yang tenang membawa kekuatan batin dan rasa percaya diri, yang sangat penting untuk kesehatan yang baik.', 'source' => 'Ajaran Spiritual Hindu'],
            ['text' => 'Kedamaian sejati ada di dalam diri kita sendiri, saat kita belajar berserah dan bersyukur atas setiap momen kehidupan.', 'source' => 'Refleksi Bhagavad Gita'],
            ['text' => 'Ketika seseorang menemukan kedamaian dalam dirinya sendiri, seluruh dunia akan tampak damai.', 'source' => 'Bhagavad Gita'],
            ['text' => 'Fokuskan pikiran pada kewajibanmu dan berserah diri pada Hyang Widhi Wasa untuk hasil terbaik.', 'source' => 'Bhagavad Gita'],
            ['text' => 'Ketenangan pikiran dicapai dengan mengembangkan persahabatan, belas kasih, dan kebahagiaan.', 'source' => 'Patanjali Yoga Sutra'],
        ];

        $buddhaTips = [
            ['text' => 'Pikiran adalah segalanya. Apa yang kamu pikirkan, kamu akan menjadi seperti itu. Jagalah kedamaian pikiranmu.', 'source' => 'Dhammapada'],
            ['text' => 'Kedamaian datang dari dalam. Jangan mencarinya di luar.', 'source' => 'Ajaran Buddha'],
            ['text' => 'Kesehatan adalah anugerah yang paling besar, kepuasan adalah kekayaan yang paling berharga, kesetiaan adalah hubungan yang terbaik.', 'source' => 'Dhammapada'],
            ['text' => 'Sama seperti lilin yang tidak dapat menyala tanpa api, manusia tidak dapat hidup tanpa kehidupan spiritual.', 'source' => 'Ajaran Buddha'],
            ['text' => 'Lepaskan masa lalu, lepaskan masa depan, lepaskan masa kini. Dengan melampaui semuanya, kamu akan bebas.', 'source' => 'Dhammapada'],
        ];

        $konghucuTips = [
            ['text' => 'Orang yang bijaksana menemukan kedamaian dalam kebajikan; orang yang bajik menemukan kedamaian dalam keharmonisan.', 'source' => 'Analek Konghucu'],
            ['text' => 'Keharmonisan dalam diri membawa keharmonisan dalam keluarga, masyarakat, dan seluruh semesta.', 'source' => 'Kitab Daxue'],
            ['text' => 'Di mana pun Anda berada, pergilah dengan segenap hati Anda dan temukan ketenangan di sana.', 'source' => 'Konghucu'],
            ['text' => 'Kedamaian batin diperoleh ketika kita hidup selaras dengan alam dan menjunjung tinggi moralitas.', 'source' => 'Kitab Zhongyong'],
        ];

        $generalTips = [
            ['text' => 'Meditasi selama 10 menit sehari dapat menurunkan kadar kortisol (hormon stres) hingga 14%, meningkatkan ketenangan.', 'source' => 'Journal of Health Psychology'],
            ['text' => 'Berdoa secara rutin terbukti meningkatkan rasa harapan dan menurunkan tingkat kecemasan pada pasien paliatif.', 'source' => 'Palliative Medicine Journal'],
            ['text' => 'Menulis jurnal rasa syukur selama 5 menit sehari dapat meningkatkan kualitas tidur hingga 25%.', 'source' => 'Applied Psychology: Health and Well-Being'],
            ['text' => 'Koneksi spiritual yang kuat dapat membantu seseorang merasa lebih bermakna, meskipun menghadapi kondisi sulit.', 'source' => 'WHO Palliative Care Guidelines'],
            ['text' => 'Teknik pernapasan dalam (deep breathing) selama 4-7-8 detik membantu menenangkan sistem saraf dan mengurangi rasa cemas.', 'source' => 'Harvard Medical School'],
            ['text' => 'Mendengarkan musik yang menenangkan dapat menurunkan tekanan darah dan mengurangi persepsi nyeri.', 'source' => 'Journal of Advanced Nursing'],
            ['text' => 'Berbagi perasaan dengan orang yang dipercaya dapat meringankan beban emosional hingga 50%.', 'source' => 'American Psychological Association'],
        ];

        $tips = $generalTips;
        if ($religion === 'Islam') {
            $tips = $islamTips;
        } elseif ($religion === 'Kristen Protestan' || $religion === 'Kristen Katolik') {
            $tips = $kristenTips;
        } elseif ($religion === 'Hindu') {
            $tips = $hinduTips;
        } elseif ($religion === 'Buddha') {
            $tips = $buddhaTips;
        } elseif ($religion === 'Konghucu') {
            $tips = $konghucuTips;
        }

        $index = Carbon::today()->dayOfYear % count($tips);

        return $tips[$index];
    }
}
