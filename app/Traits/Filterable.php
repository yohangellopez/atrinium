<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter(Builder $query, Request $request, array $filterableAttributes)
    {
        foreach ($filterableAttributes as $attribute) {
            if ($request->has($attribute)) {
                $query->where($attribute, 'like', '%' . $request->input($attribute) . '%');
            }
        }

        return $query;
    }
}