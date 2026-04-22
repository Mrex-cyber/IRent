<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Request as ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RequestsSeeder extends Seeder
{
    public function run(): void
    {
        if (ServiceRequest::exists()) {
            return;
        }

        $apartments = Apartment::with('entrance.building')->get();
        $staff = User::whereHas('roles', fn ($q) => $q->where('name', 'OSBBRepresentative'))
            ->get();

        if ($apartments->isEmpty() || $staff->isEmpty()) {
            return;
        }

        $titles = [
            'Leaking pipe under sink',
            'Heating not working',
            'Elevator out of service',
            'Broken window in stairwell',
            'Electrical outage in corridor',
            'Intercom malfunction',
            'Water pressure too low',
            'Noisy neighbors complaint',
            'Roof leaking during rain',
            'Parking space dispute',
            'Light bulb replacement needed',
            'Door lock broken',
            'Flooded basement',
            'Gas smell reported',
            'Ventilation issue in bathroom',
        ];

        $descriptions = [
            'The issue has been persisting for several days and needs urgent attention.',
            'This is causing significant inconvenience to residents.',
            'Multiple residents have reported this problem.',
            'Repair is needed as soon as possible to avoid further damage.',
            'Please assign a technician to investigate.',
        ];

        $statuses = ['new', 'new', 'in_progress', 'in_progress', 'resolved', 'resolved', 'resolved', 'closed'];
        $priorities = ['low', 'medium', 'medium', 'high', 'critical'];

        $requestsPerApartment = 3;
        $totalApartments = min($apartments->count(), 40);

        foreach ($apartments->take($totalApartments) as $apartment) {
            $creator = $apartment->owner ?? $apartment->tenant;
            if (! $creator) {
                continue;
            }

            foreach (range(1, $requestsPerApartment) as $i) {
                $status = $statuses[array_rand($statuses)];
                $resolvedAt = null;
                $createdAt = Carbon::now()->subDays(rand(1, 90));

                if ($status === 'resolved' || $status === 'closed') {
                    $resolvedAt = (clone $createdAt)->addHours(rand(2, 48));
                }

                ServiceRequest::create([
                    'title' => $titles[array_rand($titles)],
                    'description' => $descriptions[array_rand($descriptions)],
                    'status' => $status,
                    'priority' => $priorities[array_rand($priorities)],
                    'apartment_id' => $apartment->id,
                    'creator_id' => $creator->id,
                    'assignee_id' => $staff->count() > 0 ? $staff[array_rand($staff->toArray())]->id : null,
                    'resolved_at' => $resolvedAt,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
    }
}
