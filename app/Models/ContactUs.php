<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = 'contact_us';

    protected $fillable = [
        'address',
        'phone',
        'whatsapp_number',
        'whatsapp_url',
        'email',
        'instagram_url',
        'facebook_url',
        'youtube_url',
        'tiktok_url',
        'latitude',
        'longitude',
    ];

    public static function current(): self
    {
        return static::query()->first() ?? new static();
    }

    public function getMapsQueryAttribute(): string
    {
        if ($this->latitude && $this->longitude) {
            return $this->latitude . ',' . $this->longitude;
        }

        return $this->address ?? '';
    }
}
