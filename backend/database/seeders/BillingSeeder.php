<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\Invoice;
use App\Models\MeterReading;
use App\Models\UtilityMeter;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    public function run(): void
    {
        if (UtilityMeter::exists()) {
            return;
        }

        $apartments = Apartment::all();

        if ($apartments->isEmpty()) {
            return;
        }

        $meterTypes = [
            ['type' => 'water', 'serial' => 'WM', 'unit' => 'm3'],
            ['type' => 'electricity', 'serial' => 'EM', 'unit' => 'kWh'],
            ['type' => 'gas', 'serial' => 'GM', 'unit' => 'm3'],
        ];

        foreach ($apartments as $apartment) {
            foreach ($meterTypes as $mt) {
                $meter = UtilityMeter::create([
                    'apartment_id' => $apartment->id,
                    'type' => $mt['type'],
                    'serial_number' => $mt['serial'].'-'.str_pad($apartment->id, 4, '0', STR_PAD_LEFT),
                    'unit' => $mt['unit'],
                ]);

                $base = match ($mt['type']) {
                    'electricity' => rand(150, 300),
                    'gas' => rand(5, 15),
                    default => rand(2, 8),
                };

                foreach (range(5, 0) as $monthsAgo) {
                    MeterReading::create([
                        'utility_meter_id' => $meter->id,
                        'value' => $base + ($monthsAgo * rand(5, 20) / 10),
                        'reading_date' => Carbon::now()->subMonths($monthsAgo)->startOfMonth(),
                        'status' => $monthsAgo > 0 ? 'verified' : 'pending',
                    ]);
                }
            }

            foreach (range(5, 0) as $monthsAgo) {
                $dueDate = Carbon::now()->subMonths($monthsAgo)->endOfMonth();
                $isPast = $monthsAgo > 0;
                $status = $isPast ? (rand(0, 3) === 0 ? 'overdue' : 'paid') : 'unpaid';

                Invoice::create([
                    'apartment_id' => $apartment->id,
                    'amount' => rand(850, 1800) / 10,
                    'due_date' => $dueDate,
                    'paid_at' => $status === 'paid' ? $dueDate->copy()->subDays(rand(1, 10)) : null,
                    'status' => $status,
                    'created_at' => $dueDate->copy()->subDays(10),
                ]);
            }
        }
    }
}
