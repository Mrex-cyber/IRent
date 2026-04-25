<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\RegistrationService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
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
