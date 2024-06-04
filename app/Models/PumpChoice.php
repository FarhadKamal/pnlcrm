<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PumpChoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'lead_id',
        'req_id',
        'product_id',
        'pump_head',
        'unit_price',
        'qty',
        'discount_price',
        'discount_percentage',
        'net_price'
    ];

    public function productInfo()
    {
        return $this->hasOne(Items::class, 'id', 'product_id');
    }
}
