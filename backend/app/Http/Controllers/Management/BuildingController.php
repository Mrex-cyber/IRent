<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApartmentResource;
use App\Http\Resources\BuildingResource;
use App\Http\Resources\EntranceResource;
use App\Models\Apartment;
use App\Models\Building;
use App\Models\Entrance;
use App\Services\BuildingService;
use Illuminate\Http\JsonResponse;

class BuildingController extends Controller
{
    public function __construct(private BuildingService $buildingService) {}

    public function index(): JsonResponse
    {
        $buildings = $this->buildingService->listBuildings();

        return response()->json(BuildingResource::collection($buildings)->resolve());
    }

    public function entrances(Building $building): JsonResponse
    {
        $entrances = $this->buildingService->listEntrances($building);

        return response()->json(EntranceResource::collection($entrances)->resolve());
    }

    public function apartments(Entrance $entrance): JsonResponse
    {
        $apartments = $this->buildingService->listApartments($entrance);

        return response()->json(ApartmentResource::collection($apartments)->resolve());
    }

    public function showApartment(Apartment $apartment): JsonResponse
    {
        $apartment = $this->buildingService->getApartment($apartment);

        return response()->json((new ApartmentResource($apartment))->resolve());
    }
}
