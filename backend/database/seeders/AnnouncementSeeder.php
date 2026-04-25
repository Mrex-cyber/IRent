<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::limit(5)->get();
        if ($users->isEmpty()) {
            return;
        }

        $items = [
            [
                'title' => 'Annual General Meeting - November 2024',
                'content' => 'Dear residents, we are pleased to announce the Annual General Meeting will be held on November 30th, 2024 at 6:00 PM in the community hall. All apartment owners are encouraged to attend. We will discuss important matters including building maintenance, financial reports, and upcoming projects. Please RSVP by November 25th.',
                'type' => 'events',
                'status' => 'published',
                'scheduled_for' => null,
            ],
            [
                'title' => 'Water System Maintenance - Building A',
                'content' => 'Please be informed that water supply will be temporarily interrupted in Building A on November 28th from 9:00 AM to 2:00 PM for routine maintenance work. We apologize for any inconvenience and appreciate your understanding. Please store water in advance if needed.',
                'type' => 'maintenance',
                'status' => 'scheduled',
                'scheduled_for' => Carbon::now()->addDays(3),
            ],
            [
                'title' => 'New Parking Regulations',
                'content' => 'Starting December 1st, new parking regulations will be in effect. All residents must register their vehicles with the management office. Visitor parking will be limited to 2 hours. Please review the complete parking policy document available at the management office.',
                'type' => 'general',
                'status' => 'draft',
                'scheduled_for' => null,
            ],
            [
                'title' => 'Holiday Decoration Guidelines',
                'content' => 'As we approach the holiday season, please review the decoration guidelines for common areas and balconies. All decorations must be fire-resistant and securely attached. Please remove decorations by January 15th. Happy holidays!',
                'type' => 'general',
                'status' => 'published',
                'scheduled_for' => null,
            ],
            [
                'title' => 'Financial Report - Q3 2024',
                'content' => 'The quarterly financial report for Q3 2024 is now available for review. All residents can access the report through the resident portal or request a printed copy from the management office. The report includes budget allocations, maintenance expenses, and reserve fund status.',
                'type' => 'financial',
                'status' => 'published',
                'scheduled_for' => null,
            ],
            [
                'title' => 'Fire Safety Drill - December 5th',
                'content' => 'A mandatory fire safety drill will be conducted on December 5th at 10:00 AM. All residents are required to participate. Please familiarize yourself with the evacuation routes posted on each floor. Your cooperation ensures the safety of all residents.',
                'type' => 'safety',
                'status' => 'scheduled',
                'scheduled_for' => Carbon::now()->addDays(7),
            ],
            [
                'title' => 'Portal and App Updates',
                'content' => 'We have updated the resident portal and mobile app with new features: payment history export, request status notifications, and improved document storage. Please update your app from the store and report any issues to support.',
                'type' => 'updates',
                'status' => 'published',
                'scheduled_for' => null,
            ],
            [
                'title' => 'Elevator Maintenance Schedule',
                'content' => 'Scheduled elevator maintenance will take place in Building B on December 10th and 11th from 8:00 AM to 4:00 PM. One elevator will remain in operation. We apologize for any inconvenience.',
                'type' => 'maintenance',
                'status' => 'scheduled',
                'scheduled_for' => Carbon::now()->addDays(12),
            ],
            [
                'title' => 'New Year Reception - January 2025',
                'content' => 'You are invited to the New Year reception for residents on January 5th, 2025 at 5:00 PM in the community hall. Light refreshments will be served. Please confirm your attendance by December 28th.',
                'type' => 'events',
                'status' => 'draft',
                'scheduled_for' => null,
            ],
        ];

        $firstUser = $users->first();
        foreach ($items as $i => $item) {
            Announcement::create([
                'user_id' => $i < 5 ? $firstUser->id : $users->random()->id,
                'title' => $item['title'],
                'content' => $item['content'],
                'type' => $item['type'],
                'status' => $item['status'],
                'scheduled_for' => $item['scheduled_for'],
                'created_at' => Carbon::now()->subDays(count($items) - $i),
            ]);
        }
    }
}
