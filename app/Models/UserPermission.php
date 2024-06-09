<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'permission_id',
    ];

    public function permissions()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }
}
