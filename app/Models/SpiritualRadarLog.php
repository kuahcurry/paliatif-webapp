<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpiritualRadarLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'score_meaning',
        'score_closeness',
        'score_peace',
        'score_fear',
        'score_loneliness',
        'symptoms',
        'symptom_pain',
        'symptom_fatigue',
        'symptom_nausea',
        'symptom_anxiety',
        'symptom_sadness',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
