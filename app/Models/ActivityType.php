<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['name','description'];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_activity_type');
    }

}
