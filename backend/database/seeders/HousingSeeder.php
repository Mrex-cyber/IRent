<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Building;
use App\Models\Entrance;
use App\Models\User;
use Illuminate\Database\Seeder;

class HousingSeeder extends Seeder
{
    public function run(): void
    {
        if (Building::exists()) {
            return;
        }

        $owners = User::whereHas('roles', fn ($q) => $q->where('name', 'ApartmentOwner'))
            ->get();
        $tenants = User::whereHas('roles', fn ($q) => $q->where('name', 'Tenant'))
            ->get();
        $staff = User::whereHas('roles', fn ($q) => $q->where('name', 'OSBBRepresentative'))
            ->get();

        $buildings = [
            ['address' => 'Khreshchatyk St, 1', 'city' => 'Kyiv'],
            ['address' => 'Lesi Ukrainky Blvd, 24', 'city' => 'Kyiv'],
            ['address' => 'Shevchenko Ave, 7', 'city' => 'Kyiv'],
        ];

        $ownerIndex = 0;
        $tenantIndex = 0;

        foreach ($buildings as $buildingData) {
            $building = Building::create($buildingData);

            foreach (range(1, 4) as $entranceNum) {
                $entrance = Entrance::create([
                    'building_id' => $building->id,
                    'name' => "Entrance {$entranceNum}",
                    'code' => strtoupper(substr($buildingData['address'], 0, 1))."-{$entranceNum}",
                ]);

                if ($staff->count() > 0) {
                    $entrance->responsibleStaff()->syncWithoutDetaching(
                        [$staff[$entranceNum % $staff->count()]->id]
                    );
                }

                $floorCount = 9;
                $aptsPerFloor = 3;

                foreach (range(1, $floorCount) as $floor) {
                    foreach (range(1, $aptsPerFloor) as $pos) {
                        $aptNumber = (($floor - 1) * $aptsPerFloor + $pos)
                            + ($entranceNum - 1) * $floorCount * $aptsPerFloor;

                        $owner = $owners->count() > 0
                            ? $owners[$ownerIndex % $owners->count()]
                            : null;
                        $ownerIndex++;

                        $tenant = null;
                        if ($tenants->count() > 0 && ($aptNumber % 3 !== 0)) {
                            $tenant = $tenants[$tenantIndex % $tenants->count()];
                            $tenantIndex++;
                        }

                        Apartment::create([
                            'entrance_id' => $entrance->id,
                            'number' => (string) $aptNumber,
                            'floor' => $floor,
                            'area' => rand(40, 120),
                            'owner_id' => $owner?->id,
                            'tenant_id' => $tenant?->id,
                        ]);
                    }
                }
            }
        }
    }
}
