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
        Schema::table('spiritual_interventions', function (Blueprint $table) {
            $table->integer('progress_points')->default(0)->after('current_step');
        });
    }

    public function down(): void
    {
        Schema::table('spiritual_interventions', function (Blueprint $table) {
            $table->dropColumn('progress_points');
        });
    }
};
