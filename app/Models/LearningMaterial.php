<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LearningMaterial extends Model
{
    use HasFactory;

    protected $table = 'learning_materials';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'level',
        'category_id',
        'video_url',
        'module_path',
        'position',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'duration_minutes' => 'integer',
        'position' => 'integer',
    ];

    /* =====================
     |  RELATIONSHIPS
     ===================== */
    public function category()
    {
        return $this->belongsTo(CubeCategory::class, 'category_id');
    }

    /* =====================
     |  SCOPES
     ===================== */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeVideo($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeModul($query)
    {
        return $query->where('type', 'modul');
    }

    /* =====================
     |  ACCESSORS
     ===================== */
    public function getYoutubeThumbnailAttribute()
    {
        if ($this->type !== 'video' || !$this->video_url) {
            return null;
        }

        preg_match(
            '/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([^\&\?\/]+)/',
            $this->video_url,
            $matches
        );

        return isset($matches[1])
            ? "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg"
            : null;
    }
}
