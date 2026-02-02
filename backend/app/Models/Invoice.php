<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['apartment_id', 'amount', 'due_date', 'paid_at', 'status'];

    protected $casts = ['due_date' => 'date', 'paid_at' => 'date'];
}
