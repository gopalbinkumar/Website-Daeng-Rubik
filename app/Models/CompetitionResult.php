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
        'user_id',
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
        'attempt1' => 'float',
        'attempt2' => 'float',
        'attempt3' => 'float',
        'attempt4' => 'float',
        'attempt5' => 'float',
        'best' => 'float',
        'average' => 'float',
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
}