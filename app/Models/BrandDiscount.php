<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandDiscount extends Model
{
    use HasFactory;
    protected $fillable = [
        'brand_name',
        'trade_discount'

    ];
}
