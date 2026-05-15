<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $templates = [
            ['title' => 'Selamat datang di Terapi Rohani', 'body' => 'Mulailah perjalanan spiritual Anda dengan mengisi cek harian.', 'type' => 'info', 'url' => '/dashboard'],
            ['title' => 'Jurnal Anda telah direspon', 'body' => 'Tenaga kesehatan telah memberikan respon pada catatan jurnal Anda.', 'type' => 'info', 'url' => '/dashboard'],
            ['title' => 'Modul edukasi baru tersedia', 'body' => 'Modul "Mengelola Cemas" telah ditambahkan. Pelajari sekarang.', 'type' => 'info', 'url' => '/edukasi-caregiver'],
            ['title' => 'Jangan lupa cek harian', 'body' => 'Anda belum mengisi cek harian hari ini. Yuk isi sekarang!', 'type' => 'reminder', 'url' => '/dashboard'],
        ];

        foreach ($users as $user) {
            foreach ($templates as $i => $template) {
                UserNotification::create([
                    'user_id' => $user->id,
                    'title' => $template['title'],
                    'body' => $template['body'],
                    'type' => $template['type'],
                    'url' => $template['url'],
                    'is_read' => $i > 1,
                ]);
            }
        }
    }
}
