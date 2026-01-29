<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrance extends Model
{
    protected $fillable = ['building_id', 'name', 'code'];

    public function building() {
        return $this->belongsTo(Building::class);
    }

    public function apartments() {
        return $this->hasMany(Apartment::class);
    }

    public function responsibleStaff() {
        return $this->belongsToMany(User::class, 'entrance_user');
    }
}
