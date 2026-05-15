<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpiritualIntervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'focus',
        'current_step',
        'total_steps',
    ];

    protected function casts(): array
    {
        return [
            'current_step' => 'integer',
            'total_steps' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
