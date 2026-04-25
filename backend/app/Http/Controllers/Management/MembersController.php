<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MembersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function __construct(private MembersService $membersService) {}

    public function index(Request $request): JsonResponse
    {
        $role = $request->query('role');
        $search = $request->query('search');

        return response()->json($this->membersService->listMembers($role, $search));
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($this->membersService->getMember($user));
    }
}
