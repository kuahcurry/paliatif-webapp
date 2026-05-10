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
        Schema::table('spiritual_radar_logs', function (Blueprint $table) {
            $table->unsignedTinyInteger('symptom_pain')->nullable()->after('symptoms');
            $table->unsignedTinyInteger('symptom_fatigue')->nullable()->after('symptom_pain');
            $table->unsignedTinyInteger('symptom_nausea')->nullable()->after('symptom_fatigue');
            $table->unsignedTinyInteger('symptom_anxiety')->nullable()->after('symptom_nausea');
            $table->unsignedTinyInteger('symptom_sadness')->nullable()->after('symptom_anxiety');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spiritual_radar_logs', function (Blueprint $table) {
            $table->dropColumn([
                'symptom_pain',
                'symptom_fatigue',
                'symptom_nausea',
                'symptom_anxiety',
                'symptom_sadness',
            ]);
        });
    }
};
