<?php

namespace App\Http\Controllers;

use App\Models\CaregiverAssessment;
use App\Models\EducationModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class EducationModuleController extends Controller
{
    public function index(Request $request): View
    {
        $tag = $request->query('tag', 'all');
        $tag = is_string($tag) ? $tag : 'all';

        $search = $request->query('q');
        $search = is_string($search) ? trim($search) : '';
        $search = str_replace(['%', '_'], ['\\%', '\\_'], $search);

        $modulesQuery = EducationModule::query()->where('is_active', true);

        if ($tag !== 'all') {
            $modulesQuery->whereJsonContains('tags', $tag);
        }

        if ($search !== '') {
            $modulesQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $modules = $modulesQuery->orderBy('title')->get();

        $assessment = $request->user()?->caregiverAssessments()
            ->orderByDesc('created_at')
            ->first();

        $recommendedTags = $this->buildRecommendedTags($assessment);
        $recommendedModules = $this->filterByTags($recommendedTags);

        $tips = [
            'Luangkan waktu sejenak untuk menarik napas dalam-dalam (4-7-8 detik) selama 5 menit setiap kali Anda merasa lelah atau tegang.',
            'Jangan ragu untuk meminta bantuan keluarga lain untuk menjaga pasien saat Anda perlu beristirahat atau menyelesaikan urusan penting.',
            'Menjaga pasien paliatif adalah tugas mulia, tetapi kesehatan fisik dan emosional Anda sebagai caregiver adalah prioritas yang sama pentingnya.',
            'Catat perubahan penting pada gejala pasien setiap hari untuk mempermudah komunikasi dengan tim medis saat konsultasi.',
            'Ciptakan suasana kamar yang tenang, bersih, dan mendapat cukup sinar matahari pagi untuk kenyamanan pasien dan Anda sendiri.',
            'Ingatlah bahwa emosi negatif seperti cemas, sedih, atau marah adalah hal yang wajar. Terima perasaan tersebut tanpa menghakimi diri sendiri.',
            'Sempatkan mengonsumsi makanan bergizi dan minum cukup air putih agar tubuh Anda tetap memiliki energi yang stabil sepanjang hari.',
            'Berbagi cerita atau beban perasaan dengan sesama caregiver dapat memberikan kelegaan emosional dan perspektif baru yang menguatkan.',
            'Berikan penghargaan pada diri sendiri atas usaha kecil yang telah Anda lakukan hari ini dalam mendampingi orang terkasih.',
            'Fokus pada momen hari ini dan hindari mencemaskan hal-hal di masa depan yang belum tentu terjadi secara berlebihan.',
            'Musik lembut, murottal, atau nyanyian rohani di latar belakang dapat membantu menenangkan ketegangan emosional pasien.',
            'Jaga komunikasi yang terbuka dan hangat dengan pasien. Terkadang, kehadiran fisik dan mendengarkan secara aktif sudah sangat berarti.',
            'Pahami batasan fisik dan mental Anda. Jika Anda merasa sangat lelah, itu adalah tanda bahwa Anda perlu beristirahat sejenak.',
            'Buat jadwal harian yang terstruktur untuk pemberian obat, waktu makan, dan istirahat agar aktivitas perawatan berjalan lebih teratur.',
            'Lakukan aktivitas ringan bersama pasien seperti membaca buku, melihat foto keluarga, atau mengobrol santai untuk menjaga kedekatan.',
            'Ingatlah untuk tetap merawat kehidupan spiritual Anda sendiri melalui doa, ibadah, atau refleksi pribadi di sela-sela waktu perawatan.'
        ];

        $tipIndex = \Carbon\Carbon::today()->dayOfYear % count($tips);
        $dailyTip = $tips[$tipIndex];

        $categoryItems = collect(['coping', 'communication', 'grief', 'spiritual_support'])->map(fn ($t) => [
            'tag' => $t,
            'label' => match ($t) {
                'coping' => 'Coping dan Tenang',
                'communication' => 'Komunikasi',
                'grief' => 'Manajemen Duka',
                'spiritual_support' => 'Dukungan Spiritual',
                default => $t,
            },
            'count' => EducationModule::where('is_active', true)->whereJsonContains('tags', $t)->count(),
            'color' => match ($t) {
                'coping' => '#f59e0b',
                'communication' => '#3b82f6',
                'grief' => '#ef4444',
                'spiritual_support' => '#22c55e',
                default => '#94a3b8',
            },
        ]);

        return view('education.index', [
            'modules' => $modules,
            'recommendedModules' => $recommendedModules,
            'recommendedTags' => $recommendedTags,
            'tag' => $tag,
            'hasAssessment' => (bool) $assessment,
            'dailyTip' => $dailyTip,
            'categoryItems' => $categoryItems,
        ]);
    }

    public function show(EducationModule $educationModule): View
    {
        if (! $educationModule->is_active) {
            abort(404);
        }

        $otherModules = EducationModule::where('is_active', true)
            ->where('id', '!=', $educationModule->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('education.show', [
            'module' => $educationModule,
            'otherModules' => $otherModules,
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
