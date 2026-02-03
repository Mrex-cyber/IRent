<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\RegistrationService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    #[OA\Post(
        path: '/auth/register',
        summary: 'Register a new user',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['firstName', 'lastName', 'email', 'password', 'passwordConfirmation', 'role'],
                properties: [
                    new OA\Property(property: 'firstName', type: 'string', example: 'John'),
                    new OA\Property(property: 'lastName', type: 'string', example: 'Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                    new OA\Property(property: 'passwordConfirmation', type: 'string', format: 'password', example: 'password123'),
                    new OA\Property(property: 'role', type: 'string', enum: ['Tenant', 'ApartmentOwner', 'OSBBRepresentative', 'Realtor'], example: 'Tenant'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User registered successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...'),
                        new OA\Property(
                            property: 'user',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'string', example: '1'),
                                new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
                                new OA\Property(property: 'firstName', type: 'string', example: 'John'),
                                new OA\Property(property: 'lastName', type: 'string', example: 'Doe'),
                                new OA\Property(property: 'role', type: 'string', example: 'Tenant'),
                                new OA\Property(property: 'createdAt', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'updatedAt', type: 'string', format: 'date-time'),
                            ]
                        ),
                        new OA\Property(property: 'expiresIn', type: 'integer', example: 3600),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            additionalProperties: new OA\AdditionalProperties(
                                type: 'array',
                                items: new OA\Items(type: 'string')
                            )
                        ),
                    ]
                )
            ),
        ]
    )]
    public function __invoke(RegisterRequest $request, RegistrationService $service): JsonResponse
    {
        $user = $service->registerUser($request->validated());
        $token = JWTAuth::fromUser($user);
        $ttl = config('jwt.ttl', 60);

        $userData = [
            'id' => (string) $user->id,
            'email' => $user->email,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'role' => $user->roles->first()?->name ?? 'Tenant',
            'createdAt' => $user->created_at?->toAtomString(),
            'updatedAt' => $user->updated_at?->toAtomString(),
        ];

        return response()->json([
            'token' => $token,
            'user' => $userData,
            'expiresIn' => $ttl * 60,
        ], 201);
    }
}
