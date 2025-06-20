<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'transaction_code',
        'product_id',
        'quantity_sold',
        'sale_price',
        'total_price',
        'payment_method',
        'customer_phone',
        'sale_time',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'sale_time' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getProfitAttribute(): float
    {
        return ($this->sale_price - $this->product->purchase_price) * $this->quantity_sold;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (empty($sale->transaction_code)) {
                $sale->transaction_code = 'TXN' . date('Ymd') . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($sale) {
            // Update product stock
            $sale->product->decrement('stock_quantity', $sale->quantity_sold);
        });
    }
}
