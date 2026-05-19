<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAccountSeeder extends Seeder
{
    public function run(): void
    {
        // Promote existing account
        $existing = User::where('email', 'aqefhakimi32@gmail.com')->first();
        if ($existing) {
            $existing->is_admin = true;
            $existing->email_verified_at = now();
            $existing->save();
            $this->command->info('Promoted aqefhakimi32@gmail.com to admin.');
        }

        // Create dedicated admin if it doesn't exist
        $admin = User::firstOrCreate(
            ['email' => 'admin@ruangheningcare.com'],
            [
                'name' => 'Admin Ruang Hening',
                'password' => Hash::make('Admin@RuangHening2026'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $this->command->info('Created admin@ruangheningcare.com');
        } else {
            $admin->update(['is_admin' => true, 'email_verified_at' => now()]);
            $this->command->info('Updated admin@ruangheningcare.com');
        }
    }
}
