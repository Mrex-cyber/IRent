<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Tenant', 'label' => 'Tenant'],
            ['name' => 'ApartmentOwner', 'label' => 'Apartment Owner'],
            ['name' => 'OSBBRepresentative', 'label' => 'OSBB Representative'],
            ['name' => 'Realtor', 'label' => 'Realtor'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
