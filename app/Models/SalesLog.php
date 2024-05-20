<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'lead_id',
        'log_stage',
        'log_task',
        'log_by',
        'log_next',
    ];

    public function logBy()
    {
        return $this->hasOne(User::class, 'id', 'log_by');
    }
}
