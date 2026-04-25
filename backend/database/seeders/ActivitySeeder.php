<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $templates = [
            ['action' => 'resolved_request', 'subject' => 'Resolved plumbing request', 'status' => 'completed'],
            ['action' => 'published_announcement', 'subject' => 'Published announcement about AGM', 'status' => 'completed'],
            ['action' => 'assigned_request', 'subject' => 'Assigned electrician to request', 'status' => 'in-progress'],
            ['action' => 'created_chat', 'subject' => 'Created group chat for Building A', 'status' => 'completed'],
            ['action' => 'updated_profile', 'subject' => 'Updated profile information', 'status' => 'completed'],
        ];

        foreach ($users as $user) {
            foreach (array_slice($templates, 0, 4) as $index => $template) {
                Activity::create([
                    'user_id' => $user->id,
                    'action' => $template['action'],
                    'subject' => $template['subject'],
                    'meta_data' => ['status' => $template['status']],
                    'created_at' => now()->subDays(4 - $index),
                ]);
            }
        }
    }
}
