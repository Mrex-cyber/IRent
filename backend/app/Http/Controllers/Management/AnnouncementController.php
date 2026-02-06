<?php

namespace App\Http\Controllers\Management;

use App\Contracts\AnnouncementServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct(
        private AnnouncementServiceInterface $announcementService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $announcements = $this->announcementService->listForUser($request->user());

        return response()->json(AnnouncementResource::collection($announcements)->resolve());
    }

    public function store(StoreAnnouncementRequest $request): JsonResponse
    {
        $announcement = $this->announcementService->create($request->user(), $request->validated());

        return (new AnnouncementResource($announcement))->response()->setStatusCode(201);
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement): JsonResponse
    {
        try {
            $updated = $this->announcementService->update($announcement, $request->user(), $request->validated());

            return (new AnnouncementResource($updated))->response();
        } catch (AuthorizationException) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }

    public function destroy(Request $request, Announcement $announcement): JsonResponse
    {
        try {
            $this->announcementService->delete($announcement, $request->user());

            return response()->json(null, 204);
        } catch (AuthorizationException) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    }
}
