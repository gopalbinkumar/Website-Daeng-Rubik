<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionResult extends Model
{
    use HasFactory;

    protected $table = 'competition_results';

    protected $fillable = [
        'event_id',
        'competition_category_id',
        'round_id',
        'user_id',              // ✅ WAJIB
        'participant_name',
        'rank',
        'attempt1',
        'attempt2',
        'attempt3',
        'attempt4',
        'attempt5',
        'best',
        'average',
    ];

    protected $casts = [
        'rank' => 'integer',
        'attempt1' => 'string',
        'attempt2' => 'string',
        'attempt3' => 'string',
        'attempt4' => 'string',
        'attempt5' => 'string',
        'best' => 'string',
        'average' => 'string',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function category()
    {
        return $this->belongsTo(CompetitionCategory::class, 'competition_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function round()
    {
        return $this->belongsTo(CompetitionRound::class, 'round_id');
    }

}