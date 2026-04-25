<?php

namespace App\Services;

use App\Models\Request as ServiceRequest;
use App\Models\User;
use Illuminate\Support\Collection;

class MembersService
{
    public function listMembers(?string $role = null, ?string $search = null): Collection
    {
        $query = User::with([
            'roles',
            'profile',
            'ownedApartments.entrance.building',
            'rentedApartments.entrance.building',
        ])
            ->where('is_active', true)
            ->when($role, function ($q, $role) {
                $roleMap = [
                    'Owner' => 'ApartmentOwner',
                    'Tenant' => 'Tenant',
                    'Staff' => 'OSBBRepresentative',
                ];
                if (isset($roleMap[$role])) {
                    $q->whereHas('roles', fn ($r) => $r->where('name', $roleMap[$role]));
                }
            })
            ->when($search, function ($q, $search) {
                $q->where(function ($inner) use ($search) {
                    $inner->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        return $query->get()->map(fn (User $user) => $this->formatMember($user));
    }

    public function getMember(User $user): array
    {
        $user->load([
            'roles',
            'profile.workloadStats',
            'ownedApartments.entrance.building',
            'rentedApartments.entrance.building',
            'activities' => fn ($q) => $q->latest()->take(5),
        ]);

        return $this->formatMember($user, detail: true);
    }

    private function formatMember(User $user, bool $detail = false): array
    {
        $roleName = $user->roles->first()?->name;
        $displayRole = match ($roleName) {
            'ApartmentOwner' => 'Owner',
            'Tenant' => 'Tenant',
            'OSBBRepresentative' => 'Staff Member',
            default => 'Member',
        };

        $apartment = $user->ownedApartments->first() ?? $user->rentedApartments->first();
        $apartmentLabel = $apartment
            ? $apartment->number.' – '.$apartment->entrance?->building?->address
            : null;

        $data = [
            'id' => $user->id,
            'name' => trim($user->first_name.' '.$user->last_name),
            'email' => $user->email,
            'phone' => $user->profile?->phone,
            'role' => $displayRole,
            'apartment' => $apartmentLabel,
            'memberSince' => $user->created_at->format('Y-m-d'),
            'location' => $user->profile?->address,
        ];

        if ($detail) {
            $requestsQuery = ServiceRequest::where('creator_id', $user->id)
                ->orWhere('assignee_id', $user->id);

            $totalRequests = (clone $requestsQuery)->count();
            $resolved = (clone $requestsQuery)->where('status', 'resolved')->count();
            $pending = (clone $requestsQuery)->whereIn('status', ['new', 'in_progress'])->count();
            $resolutionRate = $totalRequests > 0 ? round($resolved / $totalRequests * 100).'%' : '0%';

            $data['totalRequests'] = $totalRequests;
            $data['resolved'] = $resolved;
            $data['pending'] = $pending;
            $data['resolutionRate'] = $resolutionRate;
        }

        return $data;
    }
}
