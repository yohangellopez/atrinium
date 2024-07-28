<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricalRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'currency',
        'rate'
    ];
}
