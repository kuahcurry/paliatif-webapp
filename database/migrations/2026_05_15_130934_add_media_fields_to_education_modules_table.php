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
        Schema::table('education_modules', function (Blueprint $table) {
            $table->string('video_path')->nullable()->after('url');
            $table->string('image_path')->nullable()->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('education_modules', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'image_path']);
        });
    }
};
