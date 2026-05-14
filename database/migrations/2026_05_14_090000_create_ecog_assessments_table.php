<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ecog_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('respondent_initials', 20)->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('marital_status', 30)->nullable();
            $table->string('cancer_stage', 30)->nullable();
            $table->unsignedTinyInteger('score');
            $table->string('score_label', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecog_assessments');
    }
};
