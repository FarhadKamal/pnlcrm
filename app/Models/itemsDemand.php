<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class itemsDemand extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_type',
        'item_brand',
        'item_name',
        'item_quantity',
        'item_description',
        'customer_name',
        'customer_phone',
        'created_by'
    ];

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
