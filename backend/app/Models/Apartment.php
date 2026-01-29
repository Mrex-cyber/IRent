<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Apartment extends Model
{
    protected $fillable = ['entrance_id', 'number', 'floor', 'area', 'owner_id', 'tenant_id'];

    public function entrance() {
        return $this->belongsTo(Entrance::class);
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tenant() {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function requests() {
        return $this->hasMany(Request::class);
    }
}
