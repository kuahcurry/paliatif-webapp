<?php

namespace App\Http\Controllers;

use App\Models\EducationModule;
use App\Models\SpiritualIntervention;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrayerTreeController extends Controller
{
    private array $stageNames = [
        'Bibit',
        'Tunas',
        'Semai',
        'Tumbuh',
        'Rimbun',
        'Berdaun Lebat',
        'Berbuah',
    ];

    private array $stageThresholds = [0, 3, 9, 15, 21, 27, 33];

    private array $stageQuotes = [
        'Setiap perjalanan besar dimulai dari langkah kecil.',
        'Tunas harapan mulai tumbuh di hatimu.',
        'Terus rawat, maka ia akan semakin kuat.',
        'Pohonmu semakin kokoh dan berakar dalam.',
        'Semakin hari semakin rimbun, penuh keteduhan.',
        'Daun-daunmu lebat, tanda kehidupan yang subur.',
        'Pohonmu telah berbuah, penuh berkah dan harapan.',
    ];

    public function index(Request $request): View
    {
        $user = $request->user();

        $intervention = SpiritualIntervention::firstOrCreate(
            ['user_id' => $user->id],
            [
                'focus' => 'Harapan & Makna Hidup',
                'current_step' => 1,
                'total_steps' => 7,
                'progress_points' => 0,
            ]
        );

        $totalActivities = $this->calculateProgress($user);
        $intervention->progress_points = $totalActivities;
        $intervention->save();

        $treeStage = $this->resolveStage($totalActivities);
        $intervention->current_step = $treeStage;
        $intervention->save();

        $stageName = $this->stageNames[$treeStage - 1];
        $quote = $this->stageQuotes[$treeStage - 1];

        $completedActivities = $this->getCompletedActivities($user);

        $nextThreshold = $treeStage < 7 ? $this->stageThresholds[$treeStage] : null;
        $currentThreshold = $this->stageThresholds[$treeStage - 1];
        $progressInStage = $nextThreshold ? $totalActivities - $currentThreshold : 1;
        $stageProgress = $nextThreshold ? min(100, ($progressInStage / max(1, $nextThreshold - $currentThreshold)) * 100) : 100;

        $recommendedModules = $this->getRecommendedModules($user);

        $allFertilizers = [
            ['key' => 'journal', 'label' => 'Jurnal & Catatan', 'done' => $completedActivities['journal'], 'count' => $completedActivities['journal_count'], 'route' => 'journals.index'],
            ['key' => 'evaluation', 'label' => 'Evaluasi Perasaan', 'done' => $completedActivities['evaluation'], 'count' => $completedActivities['evaluation_count'], 'route' => 'menu.emotional-evaluation'],
            ['key' => 'assessment', 'label' => 'Pengkajian Awal', 'done' => $completedActivities['assessment'], 'count' => $completedActivities['assessment_count'], 'route' => 'menu.assessment'],
        ];

        return view('menu.prayer-tree', [
            'patientName' => $user->name,
            'patientAge' => $user->patient_age ? $user->patient_age . ' Tahun' : '--',
            'patientGender' => $user->patient_gender ?? '--',
            'treeStage' => $treeStage,
            'totalStages' => 7,
            'stageName' => $stageName,
            'quote' => $quote,
            'totalActivities' => $totalActivities,
            'stageProgress' => (int) $stageProgress,
            'nextThreshold' => $nextThreshold,
            'recommendedModules' => $recommendedModules,
            'allFertilizers' => $allFertilizers,
        ]);
    }

    private function calculateProgress($user): int
    {
        $journalCount = $user->journalEntries()->count();
        $evaluationCount = $user->emotionalEvaluations()->count();
        $swbsCount = $user->swbsAssessments()->count();
        $ecogCount = $user->ecogAssessments()->count();
        $esasCount = $user->esasAssessments()->count();

        return $journalCount + $evaluationCount + $swbsCount + $ecogCount + $esasCount;
    }

    private function resolveStage(int $points): int
    {
        for ($i = count($this->stageThresholds) - 1; $i >= 0; $i--) {
            if ($points >= $this->stageThresholds[$i]) {
                return $i + 1;
            }
        }
        return 1;
    }

    private function getCompletedActivities($user): array
    {
        $journalCount = $user->journalEntries()->count();
        $evaluationCount = $user->emotionalEvaluations()->count();
        $assessmentCount = $user->swbsAssessments()->count()
            + $user->ecogAssessments()->count()
            + $user->esasAssessments()->count();

        return [
            'journal' => $journalCount > 0,
            'journal_count' => $journalCount,
            'evaluation' => $evaluationCount > 0,
            'evaluation_count' => $evaluationCount,
            'assessment' => $assessmentCount > 0,
            'assessment_count' => $assessmentCount,
        ];
    }

    private function getRecommendedModules($user)
    {
        $hasSwbs = $user->swbsAssessments()->exists();
        $hasEcog = $user->ecogAssessments()->exists();
        $hasEsas = $user->esasAssessments()->exists();

        $tags = [];
        if ($hasSwbs) $tags[] = 'swbs';
        if ($hasEcog) $tags[] = 'ecog';
        if ($hasEsas) $tags[] = 'esas';

        $modules = EducationModule::where('is_active', true);

        if (!empty($tags)) {
            $modules->where(function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->orWhereJsonContains('tags', $tag);
                }
            });
        }

        return $modules->inRandomOrder()->take(3)->get();
    }
}
