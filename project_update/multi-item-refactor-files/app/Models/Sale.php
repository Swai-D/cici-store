<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'transaction_code',
        'subtotal',
        'discount_amount',
        'total_price',
        'payment_method',
        'customer_phone',
        'notes',
        'sale_time',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'sale_time' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_phone', 'phone');
    }

    /**
     * Faida ya order nzima = jumla ya faida za line items zake zote.
     */
    public function getProfitAttribute(): float
    {
        return $this->items->sum(function (SaleItem $item) {
            return $item->profit;
        });
    }

    /**
     * COGS ya order nzima.
     */
    public function getCogsAttribute(): float
    {
        return $this->items->sum(function (SaleItem $item) {
            return $item->cogs;
        });
    }

    /**
     * Idadi ya bidhaa (line items) tofauti kwenye order hii.
     */
    public function getItemCountAttribute(): int
    {
        return $this->items->count();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (empty($sale->transaction_code)) {
                $sale->transaction_code = 'TXN' . date('Ymd') . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
