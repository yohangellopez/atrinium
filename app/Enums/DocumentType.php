<?php

namespace App\Enums;

use Illuminate\Support\Facades\Lang;

enum DocumentType: string
{
    case DNI = 'dni';
    case CIF = 'cif';
    case NIE = 'nie';
    case NIF = 'nif';
    case PASSPORT = 'passport';
    case OTHER = 'other';

    public function label(): string
    {
        return Lang::get("document_types.{$this->value}");
    }
}