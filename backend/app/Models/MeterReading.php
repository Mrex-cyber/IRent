<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterReading extends Model
{
    protected $fillable = ['utility_meter_id', 'value', 'reading_date', 'status'];

    protected $casts = ['reading_date' => 'date'];
}
