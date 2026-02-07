<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $table = 'event_registrations';

    protected $fillable = [
        'user_id',
        'event_id',
        'participant_name',
        'participant_email',
        'participant_whatsapp',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function competitionCategories()
    {
        return $this->belongsToMany(
            \App\Models\CompetitionCategory::class,
            'event_registration_categories'
        );
    }

}