<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entry_date',
        'entry_type',
        'category',
        'content',
        'is_shareable',
        'provider_response',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'is_shareable' => 'boolean',
            'content' => 'encrypted',
            'provider_response' => 'encrypted',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
