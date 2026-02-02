<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProfileController extends Controller
{
    #[OA\Get(
        path: '/management/profile',
        summary: 'Get authenticated user profile',
        tags: ['Management'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profile retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'firstName', type: 'string', example: 'John'),
                        new OA\Property(property: 'lastName', type: 'string', example: 'Doe'),
                        new OA\Property(property: 'role', type: 'string', example: 'Tenant'),
                        new OA\Property(property: 'userType', type: 'string', example: 'Tenant'),
                        new OA\Property(property: 'phone', type: 'string', nullable: true, example: '+380501234567'),
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                        new OA\Property(property: 'address', type: 'string', example: 'Street 1, City'),
                        new OA\Property(property: 'avatar', type: 'string', nullable: true),
                        new OA\Property(property: 'responsibleEntrances', type: 'array', items: new OA\Items(type: 'integer')),
                        new OA\Property(property: 'position', type: 'string', example: 'Tenant'),
                        new OA\Property(property: 'memberSince', type: 'string', format: 'date', example: '2025-01-15'),
                        new OA\Property(property: 'apartment', type: 'string', example: '42'),
                        new OA\Property(
                            property: 'workloadStats',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'totalRequests', type: 'integer', example: 10),
                                new OA\Property(property: 'resolved', type: 'integer', example: 7),
                                new OA\Property(property: 'unresolved', type: 'integer', example: 3),
                            ]
                        ),
                        new OA\Property(
                            property: 'activityHistory',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'description', type: 'string'),
                                    new OA\Property(property: 'date', type: 'string', format: 'date'),
                                    new OA\Property(property: 'status', type: 'string'),
                                ]
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request, ProfileService $service): JsonResponse
    {
        return response()->json($service->getProfileData($request->user()));
    }

    #[OA\Put(
        path: '/management/profile',
        summary: 'Update authenticated user profile',
        tags: ['Management'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'firstName', type: 'string', example: 'John'),
                    new OA\Property(property: 'lastName', type: 'string', example: 'Doe'),
                    new OA\Property(property: 'phone', type: 'string', nullable: true, example: '+380501234567'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Profile updated successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateProfileRequest $request, ProfileService $service): JsonResponse
    {
        $service->updateProfile($request->user(), $request->validated());

        return response()->json($service->getProfileData($request->user()));
    }
}
