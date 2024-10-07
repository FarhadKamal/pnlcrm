<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesTargetLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'ref_id',
        'financial_year',
        'user_id',
        'bd_code',
        'brand_name',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december',
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
    ];
}
