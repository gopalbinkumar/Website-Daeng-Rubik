<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'end_datetime'   => 'datetime',
        'ticket_price'   => 'integer',
        'max_participants' => 'integer',
        'total_prize'    => 'integer',
    ];

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
}
