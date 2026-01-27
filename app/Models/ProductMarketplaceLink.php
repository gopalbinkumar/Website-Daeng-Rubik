<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMarketplaceLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'marketplace',
        'url',
    ];

    public const MARKETPLACE_TOKOPEDIA = 'tokopedia';
    public const MARKETPLACE_SHOPEE = 'shopee';
    public const MARKETPLACE_TIKTOK = 'tiktok_shop';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

