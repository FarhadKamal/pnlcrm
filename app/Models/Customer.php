<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'customer_name',
        'group_name',
        'address',
        'zone',
        'district',
        'division',
        'tin',
        'bin',
        'trade_license',
        'contact_person',
        'contact_mobile',
        'contact_email',
        'assign_to',
        'is_active'
    ];
}
