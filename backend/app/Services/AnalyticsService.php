<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Request as ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getSummary(?string $period = null): array
    {
        $from = $this->periodStart($period);

        $totalMembers = User::count();
        $activeMembers = User::where('is_active', true)->count();

        $requestsQuery = ServiceRequest::query()->when($from, fn ($q) => $q->where('created_at', '>=', $from));
        $messagesQuery = Message::query()->when($from, fn ($q) => $q->where('created_at', '>=', $from));

        $totalRequests = (clone $requestsQuery)->count();
        $resolvedRequests = (clone $requestsQuery)->where('status', 'resolved')->count();
        $inProgressRequests = (clone $requestsQuery)->where('status', 'in_progress')->count();
        $overdueRequests = (clone $requestsQuery)->where('status', 'new')
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->count();

        $messagesAnswered = $messagesQuery->count();

        $avgSec = ServiceRequest::whereNotNull('resolved_at')
            ->when($from, fn ($q) => $q->where('created_at', '>=', $from))
            ->value(DB::raw($this->avgSecondsExpression('created_at', 'resolved_at')));

        $avgResponseTime = $avgSec ? $this->formatDuration((int) round((float) $avgSec)) : 'N/A';

        $startingEfficiency = $totalRequests > 0
            ? (int) round(($resolvedRequests / $totalRequests) * 60)
            : 0;

        $currentEfficiency = $totalRequests > 0
            ? (int) round(($resolvedRequests / $totalRequests) * 100)
            : 0;

        $efficiencyGain = $currentEfficiency - $startingEfficiency;

        return [
            'activeMembers' => $activeMembers,
            'totalMembers' => $totalMembers,
            'avgResponseTime' => $avgResponseTime,
            'messagesAnswered' => $messagesAnswered,
            'startingEfficiency' => $startingEfficiency.'%',
            'currentEfficiency' => $currentEfficiency.'%',
            'efficiencyGain' => ($efficiencyGain >= 0 ? '+' : '').$efficiencyGain.'%',
            'taskStatus' => [
                'overdue' => $overdueRequests,
                'inProgress' => $inProgressRequests,
                'completed' => $resolvedRequests,
                'total' => $totalRequests,
            ],
        ];
    }

    private function periodStart(?string $period): ?Carbon
    {
        return match ($period) {
            'today' => Carbon::today(),
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => null,
        };
    }

    private function formatDuration(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds.'s';
        }
        if ($seconds < 3600) {
            $m = floor($seconds / 60);
            $s = $seconds % 60;

            return "{$m}m {$s}s";
        }
        $hours = round($seconds / 3600, 1);

        return $hours.' hours';
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
