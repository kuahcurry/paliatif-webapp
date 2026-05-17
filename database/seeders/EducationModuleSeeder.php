<?php

namespace Database\Seeders;

use App\Models\EducationModule;
use Illuminate\Database\Seeder;

class EducationModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'title' => 'Mengelola cemas caregiver',
                'summary' => 'Latihan singkat untuk menurunkan cemas dan menjaga fokus.',
                'type' => 'article',
                'content' => 'Tarik napas perlahan 4 hitungan, tahan 4 hitungan, hembuskan 6 hitungan. Ulangi 3-5 kali.',
                'tags' => ['coping'],
            ],
            [
                'title' => 'Doa singkat saat lelah',
                'summary' => 'Panduan doa singkat untuk menenangkan pikiran.',
                'type' => 'article',
                'content' => 'Luangkan 2-3 menit untuk membaca doa pendek dan mengingat hal yang disyukuri hari ini.',
                'tags' => ['spiritual_support', 'coping'],
            ],
            [
                'title' => 'Komunikasi empatik di fase terminal',
                'summary' => 'Cara menyampaikan dukungan tanpa menghakimi.',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=9K1a8WQsGOU',
                'tags' => ['communication'],
            ],
            [
                'title' => 'Menghadapi duka dengan sehat',
                'summary' => 'Langkah sederhana untuk memahami proses berduka.',
                'type' => 'article',
                'content' => 'Duka adalah proses. Beri ruang untuk merasakan, menulis, dan berbagi.',
                'tags' => ['grief'],
            ],
            [
                'title' => 'Dukungan spiritual keluarga',
                'summary' => 'Cara sederhana mendampingi pasien secara spiritual.',
                'type' => 'video',
                'url' => 'https://www.youtube.com/watch?v=InH4t1NNmBo',
                'tags' => ['spiritual_support'],
            ],
            [
                'title' => 'Strategi coping harian caregiver',
                'summary' => 'Checklist kecil untuk menjaga energi dan harapan.',
                'type' => 'article',
                'content' => 'Tidur cukup, minta bantuan, dan tetap terhubung dengan orang terdekat.',
                'tags' => ['coping'],
            ],
        ];

        foreach ($modules as $module) {
            EducationModule::updateOrCreate(
                ['title' => $module['title']],
                $module
            );
        }
    }
}
