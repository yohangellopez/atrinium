<?php

namespace App\Models;

use App\Enums\DocumentType;
use App\Enums\Status;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'document_type',
        'document_number',
        'contact_email',
        'status',
        'user_id'
    ];

    protected $casts = [
        'document_type' => DocumentType::class,
        'status'        => Status::class,
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityTypes()
    {
        return $this->belongsToMany(ActivityType::class, 'company_activity_type');
    }
}
