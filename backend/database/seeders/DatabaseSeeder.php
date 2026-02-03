<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $testPassword = 'password';

        $resident = User::where('email', 'resident@example.com')->first();
        if (! $resident) {
            $resident = User::factory()->create([
                'first_name' => 'Resident',
                'last_name' => 'One',
                'email' => 'resident@example.com',
                'password' => $testPassword,
                'is_active' => true,
            ]);
            $resident->roles()->attach(Role::where('name', 'Tenant')->firstOrFail());
        }

        $testUser = User::where('email', 'test@example.com')->first();
        if (! $testUser) {
            $testUser = User::factory()->create([
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
                'password' => $testPassword,
                'is_active' => true,
            ]);
            $testUser->roles()->attach(Role::where('name', 'Tenant')->firstOrFail());
        }

        $this->call([
            ActivitySeeder::class,
            AnnouncementSeeder::class,
            ConversationSeeder::class,
        ]);
    }
}
