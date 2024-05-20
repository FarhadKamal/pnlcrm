<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadDivision extends Model
{
    use HasFactory;

    protected $fillable = [
        'div_name',
        'is_active'
    ];
}
