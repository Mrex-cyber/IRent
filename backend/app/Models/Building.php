<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = ['address', 'city'];

    public function entrances()
    {
        return $this->hasMany(Entrance::class);
    }
}
