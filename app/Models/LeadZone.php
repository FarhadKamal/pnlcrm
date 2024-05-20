<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_name',
        'is_active'
    ];
}
