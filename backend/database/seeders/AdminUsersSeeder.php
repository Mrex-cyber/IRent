<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUsersSeeder extends Seeder
{
    public function run(): void
    {
        $password = 'password';

        $users = [
            [
                'first_name' => 'Tom',
                'last_name' => 'Smith',
                'email' => 'chairman@example.com',
                'role' => 'OSBBRepresentative',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'manager@example.com',
                'role' => 'OSBBRepresentative',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'email' => 'coordinator@example.com',
                'role' => 'OSBBRepresentative',
            ],
            [
                'first_name' => 'Emma',
                'last_name' => 'Davis',
                'email' => 'owner1@example.com',
                'role' => 'ApartmentOwner',
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Wilson',
                'email' => 'owner2@example.com',
                'role' => 'ApartmentOwner',
            ],
            [
                'first_name' => 'Lisa',
                'last_name' => 'Anderson',
                'email' => 'owner3@example.com',
                'role' => 'ApartmentOwner',
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Brown',
                'email' => 'tenant1@example.com',
                'role' => 'Tenant',
            ],
            [
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'email' => 'tenant2@example.com',
                'role' => 'Tenant',
            ],
            [
                'first_name' => 'Alex',
                'last_name' => 'Taylor',
                'email' => 'tenant3@example.com',
                'role' => 'Tenant',
            ],
            [
                'first_name' => 'Olivia',
                'last_name' => 'Martinez',
                'email' => 'owner4@example.com',
                'role' => 'ApartmentOwner',
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Lee',
                'email' => 'tenant4@example.com',
                'role' => 'Tenant',
            ],
            [
                'first_name' => 'Sophia',
                'last_name' => 'White',
                'email' => 'owner5@example.com',
                'role' => 'ApartmentOwner',
            ],
        ];

        foreach ($users as $data) {
            $user = User::where('email', $data['email'])->first();
            if (! $user) {
                $user = User::factory()->create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'password' => $password,
                    'is_active' => true,
                ]);
            }

            $role = Role::where('name', $data['role'])->first();
            if ($role && ! $user->roles()->where('roles.id', $role->id)->exists()) {
                $user->roles()->attach($role);
            }
        }
    }
}
