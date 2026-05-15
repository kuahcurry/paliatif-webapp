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
            'patient_age' => 62,
            'patient_rm' => '23051567',
            'patient_room' => 'Mawar 3',
        ]);

        User::where('email', 'test@example.com')->update([
            'patient_gender' => 'Perempuan',
            'patient_age' => 55,
            'patient_rm' => '24011234',
            'patient_room' => 'Melati 1',
        ]);
    }
}
