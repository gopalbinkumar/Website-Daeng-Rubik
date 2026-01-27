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
        'sku',
        'price',
        'stock',
        'category',
        'brand',
        'size',
        'difficulty_level',
        'description',
        'specs',
        'is_active',
    ];

    protected $casts = [
        'specs' => 'array',
        'is_active' => 'boolean',
    ];

    public function marketplaceLinks()
    {
        return $this->hasMany(ProductMarketplaceLink::class);
    }

    public function activeMarketplaceLinks()
    {
        return $this->marketplaceLinks()->whereNotNull('url');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}

