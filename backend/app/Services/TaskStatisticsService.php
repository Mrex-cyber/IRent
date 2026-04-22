<?php

namespace App\Services;

use App\Models\Request as ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TaskStatisticsService
{
    public function getStatistics(string $period): array
    {
        $from = $this->periodStart($period);

        $query = ServiceRequest::query()->when($from, fn ($q) => $q->where('created_at', '>=', $from));

        $total = (clone $query)->count();
        $resolved = (clone $query)->where('status', 'resolved')->count();
        $unresolved = (clone $query)->whereIn('status', ['new', 'in_progress'])->count();

        $avgSeconds = (clone $query)
            ->whereNotNull('resolved_at')
            ->value(DB::raw($this->avgSecondsExpression('created_at', 'resolved_at')));

        $avgResponseTime = $avgSeconds ? $this->formatDuration((int) round((float) $avgSeconds)) : 'N/A';

        $staffMembers = $this->buildStaffWorkload($from);

        $previousTotal = $this->previousPeriodRequestCount($period);
        $totalRequestsTrendPercent = null;
        if ($previousTotal !== null && $previousTotal > 0) {
            $totalRequestsTrendPercent = (int) round(($total - $previousTotal) / $previousTotal * 100);
        }

        return [
            'statistics' => [
                'totalRequests' => $total,
                'resolved' => $resolved,
                'unresolved' => $unresolved,
                'avgResponseTime' => $avgResponseTime,
                'totalRequestsTrendPercent' => $totalRequestsTrendPercent,
            ],
            'staffMembers' => $staffMembers,
        ];
    }

    private function previousPeriodRequestCount(string $period): ?int
    {
        $bounds = match (trim($period)) {
            'Today' => [Carbon::yesterday()->startOfDay(), Carbon::today()],
            'Week' => [Carbon::now()->copy()->startOfWeek()->subWeek(), Carbon::now()->copy()->startOfWeek()],
            'Month' => [Carbon::now()->copy()->startOfMonth()->subMonth(), Carbon::now()->copy()->startOfMonth()],
            'Year' => [Carbon::now()->copy()->startOfYear()->subYear(), Carbon::now()->copy()->startOfYear()],
            default => null,
        };

        if ($bounds === null) {
            return null;
        }

        [$from, $toExclusive] = $bounds;

        return ServiceRequest::query()
            ->where('created_at', '>=', $from)
            ->where('created_at', '<', $toExclusive)
            ->count();
    }

    private function periodStart(string $period): ?Carbon
    {
        return match ($period) {
            'Today' => Carbon::today(),
            'Week' => Carbon::now()->startOfWeek(),
            'Month' => Carbon::now()->startOfMonth(),
            'Year' => Carbon::now()->startOfYear(),
            default => null,
        };
    }

    private function formatDuration(int $seconds): string
    {
        if ($seconds < 3600) {
            return round($seconds / 60).' min';
        }

        $hours = round($seconds / 3600, 1);

        return $hours.' hours';
    }

    private function buildStaffWorkload(?Carbon $from): array
    {
        $staff = User::whereHas('roles', fn ($q) => $q->where('name', 'OSBBRepresentative'))
            ->with(['profile.workloadStats', 'roles'])
            ->get();

        return $staff->map(function (User $user) use ($from) {
            $baseQuery = ServiceRequest::where('assignee_id', $user->id)
                ->when($from, fn ($q) => $q->where('created_at', '>=', $from));

            $total = (clone $baseQuery)->count();
            $resolvedCount = (clone $baseQuery)->where('status', 'resolved')->count();
            $inProgressCount = (clone $baseQuery)->whereIn('status', ['new', 'in_progress'])->count();

            $avgSec = (clone $baseQuery)
                ->whereNotNull('resolved_at')
                ->value(DB::raw($this->avgSecondsExpression('requests.created_at', 'resolved_at')));

            $avgTime = $avgSec ? $this->formatDuration((int) round((float) $avgSec)) : 'N/A';

            $stats = $user->profile?->workloadStats;
            $maxCapacity = $stats?->max_capacity ?? 25;
            $workload = $maxCapacity > 0 ? (int) min(100, round($inProgressCount / $maxCapacity * 100)) : 0;

            return [
                'id' => $user->id,
                'name' => trim($user->first_name.' '.$user->last_name),
                'role' => $user->roles->first()?->label ?? $user->roles->first()?->name ?? 'Staff',
                'avatar' => null,
                'resolved' => $resolvedCount,
                'inProgress' => $inProgressCount,
                'avgTime' => $avgTime,
                'total' => $total,
                'workload' => $workload,
            ];
        })->values()->all();
    }

    private function avgSecondsExpression(string $createdColumn, string $resolvedColumn): string
    {
        $driver = DB::connection()->getDriverName();

        return match ($driver) {
            'sqlite' => "AVG((julianday({$resolvedColumn}) - julianday({$createdColumn})) * 86400.0) as avg_sec",
            default => "AVG(TIMESTAMPDIFF(SECOND, {$createdColumn}, {$resolvedColumn})) as avg_sec",
        };
    }
}
