<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = ['title', 'description', 'status', 'priority', 'apartment_id', 'creator_id', 'assignee_id', 'resolved_at'];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function apartment() {
        return $this->belongsTo(Apartment::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee() {
        return $this->belongsTo(User::class, 'assignee_id');
    }
}
