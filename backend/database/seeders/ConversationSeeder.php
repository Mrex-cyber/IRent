<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        $sender = User::find(1);
        $other = User::where('email', 'test@example.com')->first();
        if (! $sender || ! $other) {
            return;
        }

        $conversations = [
            ['subject' => 'General Discussion', 'category' => 'general'],
            ['subject' => 'Building Maintenance', 'category' => 'maintenance'],
            ['subject' => 'Suggestions', 'category' => 'suggestions_complaints'],
        ];

        foreach ($conversations as $conv) {
            $c = Conversation::create([
                'type' => 'direct',
                'category' => $conv['category'],
                'subject' => $conv['subject'],
                'last_message_at' => now(),
            ]);
            $c->participants()->attach([$sender->id, $other->id]);
        }

        $general = Conversation::where('subject', 'General Discussion')->first();
        if ($general) {
            Message::create([
                'conversation_id' => $general->id,
                'user_id' => 1,
                'body' => 'Hello, I have a question about the common areas.',
            ]);
            Message::create([
                'conversation_id' => $general->id,
                'user_id' => 1,
                'body' => 'When is the next residents meeting?',
            ]);
            $general->update(['last_message_at' => now()]);
        }

        $maintenance = Conversation::where('subject', 'Building Maintenance')->first();
        if ($maintenance) {
            Message::create([
                'conversation_id' => $maintenance->id,
                'user_id' => 1,
                'body' => 'The elevator has been making noise on the 3rd floor.',
            ]);
            Message::create([
                'conversation_id' => $maintenance->id,
                'user_id' => 1,
                'body' => 'Could someone check the heating in apartment 302?',
            ]);
            $maintenance->update(['last_message_at' => now()]);
        }

        $suggestions = Conversation::where('subject', 'Suggestions')->first();
        if ($suggestions) {
            Message::create([
                'conversation_id' => $suggestions->id,
                'user_id' => 1,
                'body' => 'I would like to suggest installing a bike rack in the courtyard.',
            ]);
            $suggestions->update(['last_message_at' => now()]);
        }
    }
}
