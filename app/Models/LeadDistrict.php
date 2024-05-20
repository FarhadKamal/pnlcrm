<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadDistrict extends Model
{
    use HasFactory;

    protected $fillable = [
        'dist_name',
        'is_active'
    ];
}
