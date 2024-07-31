<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpareItems extends Model
{
    use HasFactory;
    protected $fillable = [
        'old_code',
        'new_code',
        'mat_name',
        'brand_name',
        'country_name',
        'unit_name'

    ];
}
