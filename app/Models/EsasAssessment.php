<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EsasAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pain',
        'fatigue',
        'nausea',
        'stress',
        'anxiety',
        'drowsiness',
        'appetite',
        'wellbeing',
        'shortness_of_breath',
        'other_problem',
        'total_score',
    ];

    protected $casts = [
        'pain' => 'integer',
        'fatigue' => 'integer',
        'nausea' => 'integer',
        'stress' => 'integer',
        'anxiety' => 'integer',
        'drowsiness' => 'integer',
        'appetite' => 'integer',
        'wellbeing' => 'integer',
        'shortness_of_breath' => 'integer',
        'other_problem' => 'integer',
        'total_score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
