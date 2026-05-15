<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('patient_gender')->nullable()->after('is_admin');
            $table->tinyInteger('patient_age')->nullable()->after('patient_gender');
            $table->string('patient_rm')->nullable()->after('patient_age');
            $table->string('patient_room')->nullable()->after('patient_rm');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['patient_gender', 'patient_age', 'patient_rm', 'patient_room']);
        });
    }
};
