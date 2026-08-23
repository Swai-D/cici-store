<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'purchase_price',
        'line_total',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Faida ya line hii moja: (bei ya kuuzia - bei ya kununua) * idadi.
     * Tunatumia purchase_price iliyo-"snapshot" wakati wa mauzo, sio ya sasa,
     * ili historia ya faida isibadilike hata bei ya bidhaa ikibadilika baadaye.
     */
    public function getProfitAttribute(): float
    {
        return ((float) $this->unit_price - (float) $this->purchase_price) * $this->quantity;
    }

    public function getCogsAttribute(): float
    {
        return (float) $this->purchase_price * $this->quantity;
    }
}
