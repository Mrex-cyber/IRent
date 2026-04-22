<?php

namespace App\Services;

use App\Models\Apartment;
use App\Models\Building;
use App\Models\Entrance;
use Illuminate\Support\Collection;

class BuildingService
{
    public function listBuildings(): Collection
    {
        return Building::withCount('entrances')->get();
    }

    public function listEntrances(Building $building): Collection
    {
        return $building->entrances()->withCount('apartments')->get();
    }

    public function listApartments(Entrance $entrance): Collection
    {
        return $entrance->apartments()
            ->with([
                'owner.profile',
                'tenant.profile',
                'meters.lastReading',
                'currentInvoice',
            ])
            ->withCount([
                'requests',
                'requests as requests_pending_count' => fn ($q) => $q->whereIn('status', ['new', 'in_progress']),
                'requests as requests_resolved_count' => fn ($q) => $q->where('status', 'resolved'),
            ])
            ->get();
    }

    public function getApartment(Apartment $apartment): Apartment
    {
        return $apartment->loadCount([
            'requests',
            'requests as requests_pending_count' => fn ($q) => $q->whereIn('status', ['new', 'in_progress']),
            'requests as requests_resolved_count' => fn ($q) => $q->where('status', 'resolved'),
        ])->load([
            'owner.profile',
            'tenant.profile',
            'meters.lastReading',
            'currentInvoice',
        ]);
    }
}
