<?php

namespace Database\Seeders;

use App\Models\EducationModule;
use Illuminate\Database\Seeder;

class ActivityModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'title' => 'Doa Pagi Islami',
                'summary' => 'Kumpulan doa pagi hari sesuai sunnah untuk memohon ketenangan dan keberkahan.',
                'type' => 'article',
                'tags' => ['spiritual_support'],
                'content' => "Bacaan Doa Pagi Hari Islami\n\n1. Doa Memohon Perlindungan\n\"Allahumma bika ashahnaa wa bika amsainaa, wa bika nahyaa wa bika namuutu, wa ilaikannusyuur.\"\nArtinya: \"Ya Allah, dengan-Mu kami memasuki waktu pagi, dengan-Mu kami memasuki waktu petang, dengan-Mu kami hidup, dengan-Mu kami mati, dan kepada-Mu (kami semua) akan dibangkitkan.\"\n\n2. Doa Mohon Kebaikan Hari Ini\n\"Allahumma inni as-aluka khairal yaumi fathahuu wa nashrahuu wa nuurahuu wa barakatahuu wa hudaahu.\"\nArtinya: \"Ya Allah, aku memohon kepada-Mu kebaikan hari ini, kemenangan, cahaya, keberkahan, dan petunjuknya.\"\n\n3. Doa Perlindungan dari Keburukan\n\"Allahumma inni a'udzu bika minal kasali wa su-il kibar, wa a'udzu bika min 'adzabin naari wa 'adzabil qabri.\"\nArtinya: \"Ya Allah, aku berlindung kepada-Mu dari kemalasan dan keburukan hari tua, dan aku berlindung kepada-Mu dari siksa neraka dan siksa kubur.\"\n\n4. Doa Memohon Ketenangan\n\"Allahumma thahhir qalbi minal ghilli wal hasadi wal kibr, wa yassir li amri, waftah li abwaba rahmatika.\"\nArtinya: \"Ya Allah, sucikan hatiku dari kedengkian, hasad, dan kesombongan. Permudahkan urusanku, dan bukakan pintu rahmat-Mu.\"\n\nCara Mengamalkan:\n- Baca setelah shalat Subuh\n- Baca dengan penuh penghayatan\n- Ambil waktu 3-5 menit untuk merenungkan maknanya\n- Disertai dengan doa pribadi sesuai kebutuhan",
                'is_active' => true,
            ],
            [
                'title' => 'Dzikir & Istighfar Pagi Petang',
                'summary' => 'Panduan dzikir pagi dan petang untuk menenangkan hati dan mendekatkan diri kepada Allah.',
                'type' => 'article',
                'tags' => ['coping'],
                'content' => "Dzikir & Istighfar Pagi Petang\n\n1. Istighfar (11x)\n\"Astaghfirullahal 'adzim, alladzi laa ilaaha illa huwal hayyul qayyum, wa atuubu ilaih.\"\n(Dibaca minimal 11 kali)\nArtinya: \"Aku memohon ampun kepada Allah Yang Maha Agung, tidak ada Tuhan selain Dia Yang Maha Hidup lagi Maha Berdiri Sendiri, dan aku bertobat kepada-Nya.\"\n\n2. Tasbih, Tahmid, Takbir (33x)\n\"Subhanallah\" (33x) — Maha Suci Allah\n\"Alhamdulillah\" (33x) — Segala puji bagi Allah\n\"Allahu Akbar\" (33x) — Allah Maha Besar\n\n3. Doa Perlindungan Diri\n\"Bismillahilladzi laa yadhurru ma'asmihi syai-un fil ardhi wa la fis samaa', wa huwas sami'ul 'aliim.\"\n(Dibaca 3x pagi dan 3x petang)\nArtinya: \"Dengan nama Allah yang bila disebut, segala sesuatu di bumi dan langit tidak akan membahayakan, dan Dia Maha Mendengar lagi Maha Mengetahui.\"\n\n4. Ayat Kursi\nAllahu laa ilaaha illa huwal hayyul qayyum... (QS. Al-Baqarah: 255)\n(Dibaca 1x setiap selesai shalat fardhu)\n\n5. Dzikir Singkat (10x)\n\"La ilaaha illallah wahdahu laa syariikalah, lahul mulku wa lahul hamdu wa huwa 'ala kulli syai-in qadiir.\"\nArtinya: \"Tidak ada Tuhan selain Allah, tidak ada sekutu bagi-Nya. Milik-Nya kerajaan dan pujian, dan Dia Maha Kuasa atas segala sesuatu.\"\n\nManfaat Dzikir:\n- Menenangkan hati dan pikiran\n- Mengurangi rasa cemas\n- Mendekatkan diri kepada Allah\n- Memberikan ketenangan batin\n\nLuangkan waktu 5-10 menit setiap pagi dan petang untuk berdzikir.",
                'is_active' => true,
            ],
            [
                'title' => 'Panduan Refleksi Diri',
                'summary' => 'Langkah-langkah sederhana untuk merefleksikan perasaan dan pengalaman hari ini.',
                'type' => 'article',
                'tags' => ['grief'],
                'content' => "Panduan Refleksi Diri\n\nLuangkan waktu 10-15 menit untuk melakukan refleksi diri. Ikuti langkah-langkah berikut:\n\nLangkah 1: Persiapan\nCari tempat yang tenang dan nyaman. Duduk dengan posisi rileks. Tarik napas dalam 3 kali.\n\nLangkah 2: Syukur\nTuliskan 3 hal yang Anda syukuri hari ini. Bisa sekecil apa pun — secangkir teh hangat, kunjungan keluarga, atau sekadar napas yang teratur.\n\nLangkah 3: Perasaan\nAmati perasaan Anda saat ini. Apakah Anda merasa:\n- Tenang dan damai?\n- Cemas atau khawatir?\n- Sedih atau kehilangan?\n- Marah atau frustrasi?\n\nTidak perlu menilai perasaan Anda. Cukup akui dan terima keberadaannya.\n\nLangkah 4: Harapan\nTuliskan 1 harapan untuk hari esok. Bisa sederhana, seperti: \"Semoga saya bisa tersenyum hari ini\" atau \"Semoga saya diberi kekuatan.\"\n\nLangkah 5: Doa\nPanjatkan doa singkat sesuai keyakinan Anda. Serahkan semua perasaan dan harapan kepada Tuhan.\n\nLangkah 6: Penutup\nTarik napas dalam 3 kali. Buka mata perlahan. Tersenyumlah.\n\nTips:\n- Lakukan refleksi di waktu yang sama setiap hari\n- Gunakan jurnal untuk mencatat refleksi Anda\n- Jangan membandingkan proses Anda dengan orang lain\n- Beri penghargaan pada diri sendiri untuk setiap langkah kecil",
                'is_active' => true,
            ],
            [
                'title' => 'Latihan Istirahat Tenang',
                'summary' => 'Panduan relaksasi dan pernapasan untuk menenangkan pikiran dan tubuh.',
                'type' => 'article',
                'tags' => ['coping', 'spiritual_support'],
                'content' => "Latihan Istirahat Tenang\n\nLatihan ini dirancang untuk membantu Anda menenangkan pikiran dan tubuh. Lakukan di tempat yang tenang selama 10-15 menit.\n\nLangkah 1: Persiapan (1 menit)\nCari posisi duduk atau berbaring yang nyaman. Longgarkan pakaian yang ketat. Matikan ponsel atau jauhkan dari jangkauan.\n\nLangkah 2: Pernapasan Dalam (3 menit)\nTarik napas perlahan melalui hidung selama 4 hitungan.\nTahan napas selama 4 hitungan.\nHembuskan napas perlahan melalui mulut selama 6 hitungan.\nUlangi 5-10 kali.\n\nLangkah 3: Relaksasi Tubuh (3 menit)\nMulai dari ujung kaki, rasakan dan rilekskan setiap bagian tubuh:\n- Kaki dan jari kaki\n- Betis dan paha\n- Perut dan punggung\n- Tangan dan lengan\n- Bahu dan leher\n- Wajah dan rahang\n\nLangkah 4: Visualisasi (3 menit)\nTutup mata. Bayangkan tempat yang paling menenangkan bagi Anda — pantai, taman, atau masjid. Rasakan suasana di tempat itu. Dengarkan suara-suara di sekitarnya. Hirup udara segar.\n\nLangkah 5: Afirmasi Diri (2 menit)\nUlangi dalam hati kalimat-kalimat berikut:\n\"Saya tenang dan damai.\"\n\"Saya kuat dan mampu melewati ini.\"\n\"Saya berharga dan dicintai.\"\n\"Setiap napas adalah berkah.\"\n\nLangkah 6: Kembali (1 menit)\nGerakkan jari tangan dan kaki perlahan. Regangkan tubuh. Buka mata. Tersenyumlah.\n\nCatatan:\n- Lakukan latihan ini setiap kali merasa cemas atau lelah\n- Tidak perlu sempurna, yang penting adalah konsistensi\n- Jika pikiran mengembara, bawa kembali ke napas Anda\n- Latihan ini dapat dilakukan kapan saja, di mana saja",
                'is_active' => true,
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
