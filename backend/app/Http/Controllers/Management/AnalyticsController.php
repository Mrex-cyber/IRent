<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(private AnalyticsService $analyticsService) {}

    public function index(Request $request): JsonResponse
    {
        $period = $request->query('period');

        return response()->json($this->analyticsService->getSummary($period));
    }
}
