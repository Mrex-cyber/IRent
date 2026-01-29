<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function getTaskOverview() {
        $query = Request::where('created_at', '>=', now()->subMonth());

        $total = $query->count();

        $resolved = (clone $query)->where('status', 'resolved')->count();

        $unresolved = (clone $query)->whereIn('status', ['new', 'in_progress'])->count();

        $avgTime = Request::whereNotNull('resolved_at')
            ->where('created_at', '>=', now()->subMonth())
            ->selectRaw('AVG(EXTRACT(EPOCH FROM (resolved_at - created_at))/3600) as avg_hours')
            ->value('avg_hours');

        return [
            'total' => $total,
            'resolved' => $resolved,
            'unresolved' => $unresolved,
            'avg_time' => round($avgTime, 1)
        ];
    }
}
