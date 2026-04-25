<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilityMeter extends Model
{
    protected $fillable = ['apartment_id', 'type', 'serial_number', 'unit'];

    public function lastReading()
    {
        return $this->hasOne(MeterReading::class)->latestOfMany('reading_date');
    }

    public function readings()
    {
        return $this->hasMany(MeterReading::class);
    }
}
