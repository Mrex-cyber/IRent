<?php

namespace App\Http\Controllers\Management;

use App\Contracts\ProfileServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request, ProfileServiceInterface $profileService): JsonResponse
    {
        return response()->json($profileService->getProfileData($request->user()));
    }

    public function update(UpdateProfileRequest $request, ProfileServiceInterface $profileService): JsonResponse
    {
        $profileService->updateProfile($request->user(), $request->validated());

        return response()->json($profileService->getProfileData($request->user()));
    }
}
