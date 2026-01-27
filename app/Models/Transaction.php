<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'cart_id',
        'source',
        'status',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'receiver_postal_code',
        'shipping_zone',
        'shipping_city',
        'shipping_province',
        'subtotal_amount',
        'shipping_cost',
        'total_amount',
        'payment_method',
        'payment_bank_name',
        'payment_account_name',
        'payment_account_number',
        'payment_proof_path',
        'paid_at',
    ];

    protected $casts = [
        'subtotal_amount' => 'integer',
        'shipping_cost' => 'integer',
        'total_amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function scopeDateRange($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
        return $query;
    }

    public function scopeByPeriod($query, ?string $period)
    {
        if ($period === 'daily') {
            return $query->whereDate('created_at', today());
        }
        if ($period === 'monthly') {
            return $query->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month);
        }
        if ($period === 'yearly') {
            return $query->whereYear('created_at', now()->year);
        }
        return $query;
    }
}

