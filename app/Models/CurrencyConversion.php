<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyConversion extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'from_currency', 'to_currency', 'amount', 'converted_amount', 'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
