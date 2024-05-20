<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'loc_name',
        'is_active'
    ];
}
