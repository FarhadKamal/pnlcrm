<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = [
        'created_by',
        'customer_id',
        'lead_source',
        'product_requirement',
        'lead_email',
        'lead_phone',
        'lead_source',
        'current_stage',
        'current_subStage',
        'is_return',
        'is_won',
        'is_lost',
        'lost_reason',
        'lost_description'
    ];
}
