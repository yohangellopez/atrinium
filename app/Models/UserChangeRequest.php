<?php

namespace App\Models;

use App\Enums\ChangeRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'user_id', 'role_id','status'];

    protected $casts = [
        'status' => ChangeRequestStatus::class,
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
