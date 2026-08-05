<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'stock',
        'condition',
        'product_category_id',
        'brand',
        'difficulty_level',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'condition' => 'baru',
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

    // 🧊 RELASI KATEGORI PRODUK
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

}
