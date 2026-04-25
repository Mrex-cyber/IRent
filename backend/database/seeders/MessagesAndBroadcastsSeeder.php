<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MessagesAndBroadcastsSeeder extends Seeder
{
    public function run(): void
    {
        $staff = User::whereHas('roles', fn ($q) => $q->where('name', 'OSBBRepresentative'))
            ->get();
        $residents = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['ApartmentOwner', 'Tenant']))
            ->get();

        if ($staff->isEmpty() || $residents->isEmpty()) {
            return;
        }

        $chairman = $staff->first();

        $directThreads = [
            [
                'subject' => 'Noise complaint - apt 5',
                'category' => 'suggestions_complaints',
                'messages' => [
                    ['from' => 'resident', 'body' => 'There is constant noise from the apartment above me after 11 PM.'],
                    ['from' => 'staff', 'body' => 'Thank you for reporting this. We will contact the resident on the floor above.'],
                    ['from' => 'resident', 'body' => 'Thank you, I appreciate it.'],
                ],
            ],
            [
                'subject' => 'Water meter replacement',
                'category' => 'maintenance',
                'messages' => [
                    ['from' => 'resident', 'body' => 'My water meter is showing unusual readings. Could it be faulty?'],
                    ['from' => 'staff', 'body' => 'We will schedule an inspection this week. Please stay available on Thursday morning.'],
                ],
            ],
            [
                'subject' => 'Parking space allocation',
                'category' => 'general',
                'messages' => [
                    ['from' => 'resident', 'body' => 'I would like to request a second parking space for my family.'],
                    ['from' => 'staff', 'body' => 'We currently have a waiting list. I have added your name. Expected availability in 2 months.'],
                    ['from' => 'resident', 'body' => 'Understood, thank you for the information.'],
                ],
            ],
            [
                'subject' => 'Invoice query for November',
                'category' => 'financial',
                'messages' => [
                    ['from' => 'resident', 'body' => 'My November invoice seems higher than usual. Can you clarify?'],
                    ['from' => 'staff', 'body' => 'The increase is due to the annual elevator maintenance fund contribution. Full details were sent in the October announcement.'],
                ],
            ],
            [
                'subject' => 'Stairwell lighting repair',
                'category' => 'maintenance',
                'messages' => [
                    ['from' => 'resident', 'body' => 'The light on the 3rd floor stairwell has been out for a week.'],
                    ['from' => 'staff', 'body' => 'Thank you. We will dispatch maintenance tomorrow.'],
                    ['from' => 'staff', 'body' => 'The bulb has been replaced. Please confirm it is working.'],
                    ['from' => 'resident', 'body' => 'Yes, it is working now. Thank you!'],
                ],
            ],
        ];

        $residentPool = $residents->shuffle();

        foreach ($directThreads as $index => $thread) {
            $resident = $residentPool[$index % $residentPool->count()];

            $existing = Conversation::where('subject', $thread['subject'])->first();
            if ($existing) {
                continue;
            }

            $lastTime = Carbon::now()->subDays(count($directThreads) - $index);
            $conv = Conversation::create([
                'type' => 'direct',
                'category' => $thread['category'],
                'subject' => $thread['subject'],
                'last_message_at' => $lastTime,
            ]);

            $conv->participants()->attach([$chairman->id, $resident->id]);

            foreach ($thread['messages'] as $msgIndex => $msg) {
                $sender = $msg['from'] === 'staff' ? $chairman : $resident;
                Message::create([
                    'conversation_id' => $conv->id,
                    'user_id' => $sender->id,
                    'body' => $msg['body'],
                    'created_at' => (clone $lastTime)->subMinutes((count($thread['messages']) - $msgIndex) * 15),
                ]);
            }
        }

        if ($staff->count() > 1) {
            $groupConvExisting = Conversation::where('subject', 'Staff Coordination')->first();
            if (! $groupConvExisting) {
                $groupConv = Conversation::create([
                    'type' => 'group',
                    'category' => 'general',
                    'subject' => 'Staff Coordination',
                    'last_message_at' => now()->subHours(2),
                ]);

                $groupConv->participants()->attach($staff->pluck('id')->toArray());

                $groupMessages = [
                    ['user' => $staff[0], 'body' => 'Team, please make sure all November requests are closed by end of week.'],
                    ['user' => $staff[1], 'body' => 'Understood. I have 3 still open in Building B.'],
                    ['user' => $staff[0], 'body' => 'Can you close them by Thursday?'],
                    ['user' => $staff[1], 'body' => 'Yes, will do.'],
                ];

                foreach ($groupMessages as $i => $gm) {
                    Message::create([
                        'conversation_id' => $groupConv->id,
                        'user_id' => $gm['user']->id,
                        'body' => $gm['body'],
                        'created_at' => now()->subHours(2)->addMinutes($i * 5),
                    ]);
                }
            }
        }

        if (! Conversation::where('subject', 'Building A Residents')->exists()) {
            $broadcastConv = Conversation::create([
                'type' => 'broadcast',
                'category' => 'general',
                'subject' => 'Building A Residents',
                'last_message_at' => now()->subDay(),
            ]);

            $broadcastConv->participants()->attach(
                $residents->take(6)->pluck('id')->push($chairman->id)->toArray()
            );

            Message::create([
                'conversation_id' => $broadcastConv->id,
                'user_id' => $chairman->id,
                'body' => 'Dear residents, the annual inspection of Building A will take place on December 10. Please ensure access to your utility meters between 9 AM and 1 PM.',
                'created_at' => now()->subDay(),
            ]);
        }
    }
}
