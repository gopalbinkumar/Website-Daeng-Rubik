<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'stock',
        'cube_category_id', // ✅ GANTI INI
        'brand',
        'difficulty_level',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // 🖼️ RELASI IMAGE
    public function images()
    {
        return $this->hasMany(ProductImage::class)
            ->orderBy('position');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('position', 0);
    }

    // 🔗 MARKETPLACE
    public function marketplaceLinks()
    {
        return $this->hasMany(ProductMarketplaceLink::class);
    }

    // 🧊 RELASI KATEGORI RUBIK
    public function cubeCategory()
    {
        return $this->belongsTo(CubeCategory::class);
    }
}
