<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('esas_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('pain');
            $table->unsignedTinyInteger('fatigue');
            $table->unsignedTinyInteger('nausea');
            $table->unsignedTinyInteger('stress');
            $table->unsignedTinyInteger('anxiety');
            $table->unsignedTinyInteger('drowsiness');
            $table->unsignedTinyInteger('appetite');
            $table->unsignedTinyInteger('wellbeing');
            $table->unsignedTinyInteger('shortness_of_breath');
            $table->unsignedTinyInteger('other_problem');
            $table->unsignedSmallInteger('total_score');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('esas_assessments');
    }
};
