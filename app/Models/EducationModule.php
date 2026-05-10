<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'summary',
        'type',
        'url',
        'content',
        'tags',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
