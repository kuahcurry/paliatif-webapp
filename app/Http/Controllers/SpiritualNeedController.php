<?php

namespace App\Http\Controllers;

use App\Models\SpiritualIntervention;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpiritualNeedController extends Controller
{
    private array $stepNames = [
        'Penguatan Harapan',
        'Makna Hidup',
        'Doa & Dzikir',
        'Dukungan Keluarga',
    ];

    private array $allFoci = [
        'Harapan & Makna Hidup',
        'Doa & Dzikir',
        'Dukungan Spiritual',
        'Penerimaan & Ikhtiar',
    ];

    public function index(Request $request): View
    {
        $user = $request->user();

        $intervention = SpiritualIntervention::firstOrCreate(
            ['user_id' => $user->id],
            [
                'focus' => $this->allFoci[0],
                'current_step' => 1,
                'total_steps' => 4,
            ]
        );

        $interventionSteps = [];
        for ($i = 0; $i < $intervention->total_steps; $i++) {
            $stepNumber = $i + 1;
            $interventionSteps[] = [
                'name' => $this->stepNames[$i] ?? 'Langkah ' . $stepNumber,
                'status' => $stepNumber < $intervention->current_step ? 'completed' : ($stepNumber === $intervention->current_step ? 'active' : 'pending'),
            ];
        }

        $todayPrograms = $this->buildTodayPrograms($intervention);

        return view('menu.spiritual-needs', [
            'patientName' => $user->name,
            'patientAge' => '62 Tahun',
            'patientGender' => 'Laki-laki',
            'patientRm' => 'No. RM: 23051567',
            'patientRoom' => 'Ruang: Mawar 3',
            'nurseName' => $user->name,
            'nurseRole' => $user->is_admin ? 'Admin' : 'Pasien',
            'interventionFocus' => $intervention->focus,
            'currentStep' => $intervention->current_step,
            'totalSteps' => $intervention->total_steps,
            'interventionSteps' => $interventionSteps,
            'todayPrograms' => $todayPrograms,
        ]);
    }

    private function buildTodayPrograms(SpiritualIntervention $intervention): array
    {
        $focus = $intervention->focus;

        $programs = match ($focus) {
            'Doa & Dzikir' => [
                ['title' => 'Doa Pagi', 'description' => 'Mulai hari dengan doa dan memohon ketenangan hati.'],
                ['title' => 'Dzikir Petang', 'description' => 'Dzikir singkat di sore hari untuk menenangkan pikiran.'],
                ['title' => 'Refleksi Malam', 'description' => 'Merenungkan kebaikan yang telah diterima hari ini.'],
            ],
            'Dukungan Spiritual' => [
                ['title' => 'Bacaan Rohani', 'description' => 'Membaca ayat-ayat suci atau buku rohani untuk penguatan iman.'],
                ['title' => 'Konseling Spiritual', 'description' => 'Sesi diskusi dengan pendamping rohani.'],
                ['title' => 'Doa Bersama', 'description' => 'Doa bersama keluarga atau pendamping.'],
            ],
            'Penerimaan & Ikhtiar' => [
                ['title' => 'Refleksi Diri', 'description' => 'Merenungkan perjalanan hidup dan penerimaan diri.'],
                ['title' => 'Rencana Harapan', 'description' => 'Membuat rencana kecil untuk masa depan yang lebih baik.'],
                ['title' => 'Syukur Harian', 'description' => 'Menulis 3 hal yang disyukuri hari ini.'],
            ],
            default => [
                ['title' => 'Doa Pagi', 'description' => 'Mulai hari dengan doa dan memohon ketenangan.'],
                ['title' => 'Refleksi Makna', 'description' => 'Renungkan hal-hal yang membuat hidup terasa bermakna.'],
                ['title' => 'Doa Keluarga', 'description' => 'Luangkan waktu untuk doa bersama keluarga.'],
            ],
        };

        return $programs;
    }
}
