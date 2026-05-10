<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaregiverAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'anxiety_level',
        'grief_level',
        'communication_level',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
