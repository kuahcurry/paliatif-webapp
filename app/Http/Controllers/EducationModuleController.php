<?php

namespace App\Http\Controllers;

use App\Models\CaregiverAssessment;
use App\Models\EducationModule;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class EducationModuleController extends Controller
{
    public function index(Request $request): View
    {
        $tag = $request->query('tag', 'all');
        $tag = is_string($tag) ? $tag : 'all';

        $modulesQuery = EducationModule::query()->where('is_active', true);

        if ($tag !== 'all') {
            $modulesQuery->whereJsonContains('tags', $tag);
        }

        $modules = $modulesQuery->orderBy('title')->get();

        $assessment = $request->user()?->caregiverAssessments()
            ->orderByDesc('created_at')
            ->first();

        $recommendedTags = $this->buildRecommendedTags($assessment);
        $recommendedModules = $this->filterByTags($recommendedTags);

        return view('education.index', [
            'modules' => $modules,
            'recommendedModules' => $recommendedModules,
            'recommendedTags' => $recommendedTags,
            'tag' => $tag,
            'hasAssessment' => (bool) $assessment,
        ]);
    }

    private function buildRecommendedTags(?CaregiverAssessment $assessment): array
    {
        if (! $assessment) {
            return [];
        }

        $tags = [];

        if ($assessment->anxiety_level >= 4) {
            $tags[] = 'coping';
            $tags[] = 'spiritual_support';
        }

        if ($assessment->grief_level >= 4) {
            $tags[] = 'grief';
        }

        if ($assessment->communication_level <= 2) {
            $tags[] = 'communication';
        }

        return array_values(array_unique($tags));
    }

    private function filterByTags(array $tags): Collection
    {
        if (empty($tags)) {
            return collect();
        }

        $query = EducationModule::query()->where('is_active', true);

        $query->where(function ($builder) use ($tags) {
            foreach ($tags as $tag) {
                $builder->orWhereJsonContains('tags', $tag);
            }
        });

        return $query->orderBy('title')->get();
    }
}
