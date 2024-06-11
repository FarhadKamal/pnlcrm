<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    protected $fillable = [
        'lead_id',
        'quotation_ref',
        'quotation_file',
        'is_accept',
        'accept_file',
        'accept_description',
        'is_return',
        'return_reason',
        'return_description'
    ];
}
