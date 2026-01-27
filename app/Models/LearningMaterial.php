<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'level',
        'category',
        'video_provider',
        'video_url',
        'video_path',
        'duration_seconds',
        'views_count',
        'rating',
        'is_published',
        'position',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'views_count' => 'integer',
        'rating' => 'integer',
        'is_published' => 'boolean',
    ];

    /**
     * Accessor untuk durasi dalam format menit:detik (mis. "5:30").
     */
    public function getDurationLabelAttribute(): string
    {
        $seconds = $this->duration_seconds ?? 0;
        $minutes = intdiv($seconds, 60);
        $sec = $seconds % 60;

        return sprintf('%d:%02d', $minutes, $sec);
    }
}
