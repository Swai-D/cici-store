<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'last_purchase_date',
        'total_spent',
    ];

    protected $casts = [
        'last_purchase_date' => 'date',
        'total_spent' => 'decimal:2',
    ];
}
