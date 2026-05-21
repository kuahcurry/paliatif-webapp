<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmotionalEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_number',
        'emotion',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'session_number' => 'integer',
            'note' => 'encrypted',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
