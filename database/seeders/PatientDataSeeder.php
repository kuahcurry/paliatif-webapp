<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class PatientDataSeeder extends Seeder
{
    public function run(): void
    {
        User::where('email', 'admin@example.com')->update([
            'patient_gender' => 'Laki-laki',
            'patient_birth_date' => '1964-05-15',
        ]);

        User::where('email', 'test@example.com')->update([
            'patient_gender' => 'Perempuan',
            'patient_birth_date' => '1969-03-20',
        ]);
    }
}
