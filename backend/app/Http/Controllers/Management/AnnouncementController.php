<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Models\Announcement;
use App\Services\AnnouncementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct(
        private AnnouncementService $announcementService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $announcements = $this->announcementService->listForUser($request->user());

        return response()->json($announcements);
    }

    public function store(StoreAnnouncementRequest $request): JsonResponse
    {
        $announcement = $this->announcementService->create($request->user(), $request->validated());

        return response()->json($this->announcementService->format($announcement), 201);
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement): JsonResponse
    {
        try {
            $announcement = $this->announcementService->update($announcement, $request->user(), $request->validated());

            return response()->json($this->announcementService->format($announcement));
        } catch (\Illuminate\Auth\Access\AuthorizationException) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    public function destroy(Request $request, Announcement $announcement): JsonResponse
    {
        try {
            $this->announcementService->delete($announcement, $request->user());

            return response()->json(null, 204);
        } catch (\Illuminate\Auth\Access\AuthorizationException) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }
}
