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
            $table->text('symptoms')->nullable()->after('score_loneliness');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spiritual_radar_logs', function (Blueprint $table) {
            $table->dropColumn('symptoms');
        });
    }
};
