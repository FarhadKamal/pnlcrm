<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'lead_id',
        'quotation_id',
        'deposit_date',
        'pay_amount',
        'transaction_type',
        'transaction_by',
        'transaction_file',
        'transaction_remarks',
        'is_verified',
        'verified_by',
        'deposited_date',
        'deposited_remarks',
        'is_return',
        'return_date',
        'return_remarks',
        'return_by',
    ];
}
