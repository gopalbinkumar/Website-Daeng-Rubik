<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionRound extends Model
{
    use HasFactory;

    protected $table = 'competition_rounds';

    protected $fillable = [
        'event_id',
        'competition_category_id',
        'round_number',
        'name',
        'cutoff',
        'advancement',
    ];

    protected $casts = [
        'round_number' => 'integer',
        'cutoff' => 'integer',
        'advancement' => 'integer',
    ];

    /* =========================
     | RELATIONS
     ========================= */

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function category()
    {
        return $this->belongsTo(
            CompetitionCategory::class,
            'competition_category_id'
        );
    }

    public function results()
    {
        return $this->hasMany(
            CompetitionResult::class,
            'round_id'
        );
    }
}
