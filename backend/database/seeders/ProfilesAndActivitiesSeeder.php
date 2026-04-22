<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\WorkloadStats;
use Illuminate\Database\Seeder;

class ProfilesAndActivitiesSeeder extends Seeder
{
    public function run(): void
    {
        $phones = [
            '+38 099 111 11 11',
            '+38 099 222 22 22',
            '+38 099 333 33 33',
            '+38 099 444 44 44',
            '+38 099 555 55 55',
            '+38 099 666 66 66',
            '+38 099 777 77 77',
            '+38 099 888 88 88',
            '+38 099 999 99 99',
        ];

        $staffActivities = [
            ['action' => 'resolved_request', 'subject' => 'Resolved plumbing issue in apt 12', 'status' => 'completed'],
            ['action' => 'published_announcement', 'subject' => 'Published parking regulation update', 'status' => 'completed'],
            ['action' => 'assigned_request', 'subject' => 'Assigned electrician to request #45', 'status' => 'in-progress'],
            ['action' => 'created_conversation', 'subject' => 'Created group chat for Building A residents', 'status' => 'completed'],
            ['action' => 'updated_profile', 'subject' => 'Updated contact information', 'status' => 'completed'],
        ];

        $residentActivities = [
            ['action' => 'submitted_request', 'subject' => 'Submitted heating malfunction request', 'status' => 'pending'],
            ['action' => 'paid_invoice', 'subject' => 'Paid monthly maintenance invoice', 'status' => 'completed'],
            ['action' => 'sent_message', 'subject' => 'Sent message to building manager', 'status' => 'completed'],
            ['action' => 'submitted_meter_reading', 'subject' => 'Submitted water meter reading', 'status' => 'completed'],
        ];

        $users = User::all();
        $phoneIndex = 0;

        foreach ($users as $user) {
            if (! $user->profile) {
                $profile = UserProfile::create([
                    'user_id' => $user->id,
                    'phone' => $phones[$phoneIndex % count($phones)],
                    'address' => '1 Main Street, Kyiv',
                    'bio' => null,
                ]);
                $phoneIndex++;

                $isStaff = $user->roles()->where('name', 'OSBBRepresentative')->exists();
                if ($isStaff) {
                    WorkloadStats::create([
                        'user_profile_id' => $profile->id,
                        'active_requests_count' => rand(5, 20),
                        'resolved_requests_count' => rand(50, 200),
                        'avg_response_time' => round(rand(15, 50) / 10, 1),
                        'max_capacity' => 25,
                    ]);
                }
            }

            $isStaff = $user->roles()->where('name', 'OSBBRepresentative')->exists();
            $templates = $isStaff ? $staffActivities : $residentActivities;

            if ($user->activities()->count() === 0) {
                foreach (array_slice($templates, 0, 4) as $index => $template) {
                    Activity::create([
                        'user_id' => $user->id,
                        'action' => $template['action'],
                        'subject' => $template['subject'],
                        'meta_data' => ['status' => $template['status']],
                        'created_at' => now()->subDays(6 - $index),
                    ]);
                }
            }
        }
    }
}
