<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminPanelSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUsersSeeder::class,
            HousingSeeder::class,
            ProfilesAndActivitiesSeeder::class,
            RequestsSeeder::class,
            AnnouncementSeeder::class,
            MessagesAndBroadcastsSeeder::class,
            BillingSeeder::class,
            ManagementSettingsSeeder::class,
        ]);
    }
}
