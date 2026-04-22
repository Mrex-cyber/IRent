<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\TaskStatisticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskStatisticsController extends Controller
{
    public function __construct(private TaskStatisticsService $taskStatisticsService) {}

    public function index(Request $request): JsonResponse
    {
        $period = $request->query('period', 'Week');

        return response()->json($this->taskStatisticsService->getStatistics($period));
    }
}
