<?php

namespace Database\Seeders;

use App\Models\ManagementSetting;
use Illuminate\Database\Seeder;

class ManagementSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'osbb_name',
                'value' => 'Sunrise OSBB',
                'type' => 'string',
                'group' => 'general',
                'label' => 'OSBB Name',
            ],
            [
                'key' => 'contact_email',
                'value' => 'office@sunrise-osbb.com',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Contact Email',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+38 044 123 45 67',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Contact Phone',
            ],
            [
                'key' => 'office_address',
                'value' => 'Khreshchatyk St, 1, Office 12, Kyiv',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Office Address',
            ],
            [
                'key' => 'maintenance_fee',
                'value' => '45.00',
                'type' => 'number',
                'group' => 'billing',
                'label' => 'Monthly Maintenance Fee (UAH/m²)',
            ],
            [
                'key' => 'invoice_due_days',
                'value' => '25',
                'type' => 'number',
                'group' => 'billing',
                'label' => 'Invoice Due Day of Month',
            ],
            [
                'key' => 'notification_email_enabled',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Email Notifications',
            ],
            [
                'key' => 'notification_request_updates',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Request Status Update Notifications',
            ],
            [
                'key' => 'announcement_auto_publish',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'announcements',
                'label' => 'Auto-publish Scheduled Announcements',
            ],
            [
                'key' => 'working_hours_start',
                'value' => '09:00',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Working Hours Start',
            ],
            [
                'key' => 'working_hours_end',
                'value' => '18:00',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Working Hours End',
            ],
        ];

        foreach ($settings as $setting) {
            ManagementSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
