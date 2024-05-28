<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirements extends Model
{
    use HasFactory;
    protected $fillable = [
        'lead_id',
        'type_of_use',
        'suction_type',
        'suction_pipe_dia',
        'delivery_head',
        'delivery_pipe_dia',
        'horizontal_pipe_length',
        'source_of_water',
        'water_consumption',
        'liquid_type',
        'pump_running_hour'
    ];
}
