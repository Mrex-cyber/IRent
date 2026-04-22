<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Building;
use App\Models\Entrance;
use Illuminate\Http\JsonResponse;

class BuildingController extends Controller
{
    public function index(): JsonResponse
    {
        $buildings = Building::all()->map(fn ($b) => [
            'id' => $b->id,
            'name' => $b->address,
        ]);

        return response()->json($buildings);
    }

    public function entrances(int $buildingId): JsonResponse
    {
        $entrances = Entrance::where('building_id', $buildingId)
            ->withCount('apartments')
            ->get()
            ->map(fn ($e) => [
                'id' => $e->id,
                'name' => $e->name,
                'buildingId' => $e->building_id,
                'apartmentCount' => $e->apartments_count,
            ]);

        return response()->json($entrances);
    }

    public function apartments(int $entranceId): JsonResponse
    {
        $apartments = Apartment::where('entrance_id', $entranceId)
            ->with([
                'owner.profile',
                'tenant.profile',
                'requests',
                'meters.lastReading',
                'latestInvoice',
            ])
            ->get()
            ->map(function (Apartment $apt) {
                $owner = $apt->owner;
                $pendingCount = $apt->requests->whereIn('status', ['new', 'in_progress'])->count();
                $totalRequests = $apt->requests->count();
                $resolvedRequests = $apt->requests->where('status', 'resolved')->count();

                $waterMeter = $apt->meters->where('type', 'water')->first();
                $electricityMeter = $apt->meters->where('type', 'electricity')->first();
                $gasMeter = $apt->meters->where('type', 'gas')->first();

                $invoice = $apt->latestInvoice;

                return [
                    'id' => $apt->id,
                    'number' => $apt->number,
                    'floor' => $apt->floor,
                    'area' => $apt->area,
                    'entranceId' => $apt->entrance_id,
                    'pendingCount' => $pendingCount,
                    'ownerName' => $owner ? "{$owner->first_name} {$owner->last_name}" : null,
                    'ownerPhone' => $owner?->profile?->phone,
                    'ownerEmail' => $owner?->email,
                    'requestsTotal' => $totalRequests,
                    'requestsPending' => $pendingCount,
                    'requestsResolved' => $resolvedRequests,
                    'waterMeter' => $waterMeter?->lastReading?->value,
                    'electricityMeter' => $electricityMeter?->lastReading?->value,
                    'gasMeter' => $gasMeter?->lastReading?->value,
                    'billingAmount' => $invoice?->amount,
                    'dueDate' => $invoice?->due_date?->format('Y-m-d'),
                    'billingStatus' => $invoice?->status,
                ];
            });

        return response()->json($apartments);
    }
}
