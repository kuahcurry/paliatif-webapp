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
        'is_highlighted',
        'video_path',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_active' => 'boolean',
            'is_highlighted' => 'boolean',
        ];
    }
}
