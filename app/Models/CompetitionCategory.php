<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionCategory extends Model
{
    use HasFactory;

    protected $table = 'competition_categories';

    protected $fillable = [
        'code',
        'name',
        'main_category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'main_category' => 'boolean',
    ];

    /**
     * Scope untuk kategori aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
