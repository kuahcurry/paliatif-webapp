<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EcogAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'respondent_initials',
        'age',
        'gender',
        'marital_status',
        'cancer_stage',
        'score',
        'score_label',
    ];

    protected $casts = [
        'age' => 'integer',
        'score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
