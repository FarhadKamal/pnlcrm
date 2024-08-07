<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;
    protected $fillable = [
        'old_code',
        'new_code',
        'mat_name',
        'brand_name',
        'country_name',
        'pump_type',
        'itm_group',
        'phase',
        'lead_phone',
        'kw',
        'hp',
        'suction_dia',
        'delivery_dia',
        'min_capacity',
        'max_capacity',
        'min_head',
        'max_head',
        'unit_name'
    ];

    public function TradDiscontInfo()
    {
        return $this->hasOne(BrandDiscount::class, 'brand_name', 'brand_name');
    }
}
