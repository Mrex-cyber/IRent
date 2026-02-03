<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkloadStats extends Model
{
    protected $fillable = ['user_profile_id', 'active_requests_count', 'resolved_requests_count', 'avg_response_time'];

    public function profile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id');
    }
}
