<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Request as UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    public function getProfileData(User $user): array
    {
        $user->load(['profile', 'roles', 'ownedApartments.entrance.building', 'responsibleEntrances']);
        $profile = $user->profile;
        $apartment = $user->ownedApartments->first();
        $buildingAddress = $apartment?->entrance?->building?->address ?? 'No Address';
        $apartmentNumber = $apartment ? $apartment->number : 'N/A';
        $roleName = $user->roles->first()?->name ?? 'Member';

        $requestsQuery = UserRequest::query()
            ->where('creator_id', $user->id)
            ->orWhere('assignee_id', $user->id);

        $totalRequests = $requestsQuery->count();
        $resolvedRequests = (clone $requestsQuery)->where('status', 'resolved')->count();
        $unresolvedRequests = (clone $requestsQuery)->whereIn('status', ['new', 'in_progress'])->count();

        $activityHistory = $user->activities()
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Activity $activity) => [
                'id' => $activity->id,
                'description' => $activity->subject ?? $activity->action,
                'date' => $activity->created_at->format('Y-m-d'),
                'status' => $activity->meta_data['status'] ?? $activity->action,
            ])
            ->values()
            ->all();

        $responsibleEntrances = $user->responsibleEntrances->pluck('id')->values()->all();

        return [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'role' => $roleName,
            'userType' => $roleName,
            'phone' => $profile?->phone,
            'email' => $user->email,
            'address' => $profile?->address ?? $buildingAddress,
            'avatar' => $profile?->avatar_url,
            'responsibleEntrances' => $responsibleEntrances,
            'position' => $roleName,
            'memberSince' => $user->created_at->format('Y-m-d'),
            'apartment' => $apartmentNumber,
            'workloadStats' => [
                'totalRequests' => $totalRequests,
                'resolved' => $resolvedRequests,
                'unresolved' => $unresolvedRequests,
            ],
            'activityHistory' => $activityHistory,
        ];
    }

    public function updateProfile(User $user, array $data): void
    {
        DB::transaction(function () use ($user, $data) {
            $user->update([
                'first_name' => $data['firstName'] ?? $user->first_name,
                'last_name' => $data['lastName'] ?? $user->last_name,
                'email' => $data['email'] ?? $user->email,
            ]);

            $profile = $user->profile;
            if ($profile) {
                $profile->update([
                    'phone' => $data['phone'] ?? $profile->phone,
                    'address' => array_key_exists('address', $data) ? $data['address'] : $profile->address,
                ]);
            }
        });
    }
}
