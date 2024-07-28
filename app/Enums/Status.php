<?php

namespace App\Enums;

use Illuminate\Support\Facades\Lang;

enum Status: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return Lang::get("status.{$this->value}");
    }
}