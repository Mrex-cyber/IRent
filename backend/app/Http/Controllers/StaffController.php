<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function getStaffWorkload() {
        $staff = User::whereHas('profile.workloadStats')
                     ->with('profile.workloadStats') 
                     ->get();
    
        
        return $staff->map(function ($user) {
            $stats = $user->profile->workloadStats;
            
        
            $workloadPercent = 0;
            if ($stats->max_capacity > 0) {
                $workloadPercent = ($stats->active_requests_count / $stats->max_capacity) * 100;
            }
    
            return [
                'name' => $user->profile->first_name . ' ' . $user->profile->last_name,
                'role' => $user->profile->job_title, 
                'avatar' => $user->profile->avatar_url,
                'stats' => [
                    'total_assigned' => $stats->active_requests_count + $stats->resolved_requests_count, 
                    'resolved' => $stats->resolved_requests_count, 
                    'in_progress' => $stats->active_requests_count, 
                    'avg_time' => $stats->avg_response_time . ' hours',
                    'workload_percentage' => round($workloadPercent) 
                ]
            ];
        });
    }
}