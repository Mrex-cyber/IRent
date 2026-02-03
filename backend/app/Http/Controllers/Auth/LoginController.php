<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class LoginController extends Controller
{
    #[OA\Post(
        path: '/auth/login',
        summary: 'Login user',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john.doe@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'access_token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
                        new OA\Property(
                            property: 'user',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
                                new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                                new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Invalid credentials',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Неправильний логін або пароль'),
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
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Неправильний логін або пароль'], 401);
        }

        return $this->respondWithToken($token);
    }

    #[OA\Get(
        path: '/auth/me',
        summary: 'Get authenticated user',
        tags: ['Authentication'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User information',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
                        new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                        new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
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
    public function me(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }

    #[OA\Post(
        path: '/auth/logout',
        summary: 'Logout user',
        tags: ['Authentication'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logout successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Успішний вихід'),
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
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Успішний вихід']);
    }

    #[OA\Post(
        path: '/auth/refresh',
        summary: 'Refresh authentication token',
        tags: ['Authentication'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Token refreshed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'access_token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
                        new OA\Property(
                            property: 'user',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'email', type: 'string', example: 'john.doe@example.com'),
                                new OA\Property(property: 'first_name', type: 'string', example: 'John'),
                                new OA\Property(property: 'last_name', type: 'string', example: 'Doe'),
                            ]
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
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token): JsonResponse
    {
        $user = auth('api')->user();
        $role = $user->roles()->first();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'role' => $role?->name,
            ],
        ]);
    }
}
