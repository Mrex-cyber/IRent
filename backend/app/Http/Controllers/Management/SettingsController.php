<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(private SettingsService $settingsService) {}

    public function index(): JsonResponse
    {
        return response()->json($this->settingsService->getGrouped());
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
        ]);

        $this->settingsService->updateMany($data['settings']);

        return response()->json($this->settingsService->getGrouped());
    }
}
