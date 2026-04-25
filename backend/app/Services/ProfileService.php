<?php

namespace App\Services;

use App\Contracts\ProfileServiceInterface;
use App\Models\Activity;
use App\Models\Entrance;
use App\Models\Request as UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileService implements ProfileServiceInterface
{
    public function getProfileData(User $user): array
    {
        $user->load([
            'profile.workloadStats',
            'roles',
            'ownedApartments.entrance.building',
            'rentedApartments.entrance.building',
            'responsibleEntrances.building',
        ]);
        $profile = $user->profile;
        $role = $user->roles->first();
        $roleName = $role?->name;
        $roleLabel = $role?->label ?? $roleName;

        $apartment = $user->ownedApartments->first() ?? $user->rentedApartments->first();
        $apartmentLabel = null;
        if ($apartment !== null) {
            $addr = $apartment->entrance?->building?->address;
            $apartmentLabel = $addr !== null && $addr !== ''
                ? "{$apartment->number} – {$addr}"
                : (string) $apartment->number;
        }

        $requestsQuery = UserRequest::query()
            ->where(function ($q) use ($user) {
                $q->where('creator_id', $user->id)
                    ->orWhere('assignee_id', $user->id);
            });

        $stats = $profile?->workloadStats;
        if ($stats !== null) {
            $totalRequests = (int) $stats->active_requests_count + (int) $stats->resolved_requests_count;
            $resolvedRequests = (int) $stats->resolved_requests_count;
            $unresolvedRequests = (int) $stats->active_requests_count;
        } else {
            $totalRequests = (clone $requestsQuery)->count();
            $resolvedRequests = (clone $requestsQuery)->where('status', 'resolved')->count();
            $unresolvedRequests = (clone $requestsQuery)->whereIn('status', ['new', 'in_progress'])->count();
        }

        $activityHistory = $user->activities()
            ->latest()
            ->take(5)
            ->get()
            ->map(function (Activity $activity) {
                $status = $activity->meta_data['status'] ?? $activity->action;

                return [
                    'id' => $activity->id,
                    'description' => $activity->subject ?? $activity->action,
                    'date' => $activity->created_at->format('Y-m-d'),
                    'status' => is_string($status) ? $status : (string) $status,
                ];
            })
            ->values()
            ->all();

        $responsibleEntrances = $user->responsibleEntrances->map(function (Entrance $e) {
            return [
                'id' => $e->id,
                'name' => $e->name,
                'buildingAddress' => $e->building?->address,
            ];
        })->values()->all();

        return [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'role' => $roleLabel,
            'roleName' => $roleName,
            'userType' => $user->is_active ? 'Active' : 'Inactive',
            'phone' => $profile?->phone,
            'email' => $user->email,
            'address' => $profile?->address,
            'avatar' => $profile?->avatar_url,
            'bio' => $profile?->bio,
            'responsibleEntrances' => $responsibleEntrances,
            'memberSince' => $user->created_at->format('Y-m-d'),
            'apartment' => $apartmentLabel,
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
                $updates = [
                    'phone' => $data['phone'] ?? $profile->phone,
                    'address' => array_key_exists('address', $data) ? $data['address'] : $profile->address,
                ];
                if (array_key_exists('bio', $data)) {
                    $updates['bio'] = $data['bio'];
                }
                $profile->update($updates);
            }
        });
    }
}
