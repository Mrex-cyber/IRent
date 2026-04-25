<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Request as ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskStatisticsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $period = $request->query('period', 'Week');

        $from = match (strtolower($period)) {
            'today' => Carbon::today(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfWeek(),
        };

        $query = ServiceRequest::where('created_at', '>=', $from);

        $total = $query->count();
        $resolved = (clone $query)->where('status', 'resolved')->count();
        $unresolved = (clone $query)->whereIn('status', ['new', 'in_progress'])->count();

        $avgHours = ServiceRequest::whereNotNull('resolved_at')
            ->where('created_at', '>=', $from)
            ->get()
            ->avg(function ($r) {
                return $r->created_at->diffInHours($r->resolved_at);
            });

        $staffMembers = User::whereHas('roles', fn ($q) => $q->where('name', 'OSBBRepresentative'))
            ->with(['profile.workloadStats'])
            ->get()
            ->map(function (User $user) use ($from) {
                $assignedRequests = ServiceRequest::where('assignee_id', $user->id)
                    ->where('created_at', '>=', $from)
                    ->get();

                $resolvedCount = $assignedRequests->where('status', 'resolved')->count();
                $inProgressCount = $assignedRequests->whereIn('status', ['new', 'in_progress'])->count();
                $totalCount = $assignedRequests->count();

                $avgTime = $assignedRequests
                    ->whereNotNull('resolved_at')
                    ->avg(fn ($r) => $r->created_at->diffInHours($r->resolved_at));

                $maxCapacity = $user->profile?->workloadStats?->max_capacity ?? 25;
                $workloadPercent = $maxCapacity > 0
                    ? min(100, round(($inProgressCount / $maxCapacity) * 100))
                    : 0;

                return [
                    'id' => $user->id,
                    'name' => "{$user->first_name} {$user->last_name}",
                    'role' => $user->roles->first()?->label ?? 'Staff',
                    'avatar' => null,
                    'resolved' => $resolvedCount,
                    'inProgress' => $inProgressCount,
                    'avgTime' => $avgTime ? round($avgTime, 1).' hours' : 'N/A',
                    'total' => $totalCount,
                    'workload' => $workloadPercent,
                ];
            });

        return response()->json([
            'statistics' => [
                'totalRequests' => $total,
                'resolved' => $resolved,
                'unresolved' => $unresolved,
                'avgResponseTime' => $avgHours ? round($avgHours, 1).' hours' : '0 hours',
            ],
            'staffMembers' => $staffMembers->values(),
        ]);
    }
}
