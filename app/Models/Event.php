<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'title',
        'category',
        'custom_category',
        'description',
        'start_datetime',
        'end_datetime',
        'location',
        'cover_image',
        'ticket_price',
        'max_participants',
        'total_prize',
        'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'ticket_price' => 'integer',
        'max_participants' => 'integer',
        'total_prize' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }


    /**
     * Relasi ke kategori lomba (WCA)
     * many-to-many
     */
    public function competitionCategories()
    {
        return $this->belongsToMany(
            CompetitionCategory::class,
            'event_competition_categories'
        );
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_registrations')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('status')
            ->withTimestamps();
    }


    public function competitionResults()
    {
        return $this->hasMany(CompetitionResult::class);
    }

    /**
     * Scope event kompetisi
     */
    public function scopeCompetition($query)
    {
        return $query->where('category', 'kompetisi');
    }

    /**
     * Scope event upcoming
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    /**
     * Helper: cek apakah event kompetisi
     */
    public function isCompetition()
    {
        return $this->category === 'kompetisi';
    }

    public function getBadgeClassAttribute()
    {
        return match ($this->category) {
            'kompetisi' => 'hot',
            default => 'muted',
        };
    }

    public function getBadgeLabelAttribute()
    {
        return ucfirst($this->category);
    }

}
