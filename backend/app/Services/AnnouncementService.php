<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementService
{
    public function listForUser(User $user): array
    {
        return Announcement::with('author')
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(fn (Announcement $a) => $this->format($a))
            ->values()
            ->all();
    }

    public function create(User $user, array $data): Announcement
    {
        $announcement = Announcement::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'content' => $data['content'],
            'type' => strtolower($data['category']),
            'status' => strtolower($data['status']),
            'scheduled_for' => ! empty($data['scheduledAt']) ? $data['scheduledAt'] : null,
        ]);

        if ($announcement->status === 'published') {
            $announcement->touch();
        }

        return $announcement->load('author');
    }

    public function update(Announcement $announcement, User $user, array $data): Announcement
    {
        if ($announcement->user_id !== $user->id) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Unauthorized');
        }

        $payload = [];
        if (array_key_exists('title', $data)) {
            $payload['title'] = $data['title'];
        }
        if (array_key_exists('content', $data)) {
            $payload['content'] = $data['content'];
        }
        if (array_key_exists('category', $data)) {
            $payload['type'] = strtolower($data['category']);
        }
        if (array_key_exists('status', $data)) {
            $payload['status'] = strtolower($data['status']);
        }
        if (array_key_exists('scheduledAt', $data)) {
            $payload['scheduled_for'] = $data['scheduledAt'] ?: null;
        }

        $announcement->update($payload);

        return $announcement->load('author');
    }

    public function delete(Announcement $announcement, User $user): void
    {
        if ($announcement->user_id !== $user->id) {
            throw new \Illuminate\Auth\Access\AuthorizationException('Unauthorized');
        }

        $announcement->delete();
    }

    public function format(Announcement $a): array
    {
        $author = $a->relationLoaded('author') ? $a->author : $a->author()->first();

        return [
            'id' => $a->id,
            'title' => $a->title,
            'content' => $a->content,
            'category' => ucfirst($a->type),
            'status' => ucfirst($a->status),
            'publishedAt' => $a->status === 'published' ? $a->updated_at?->format('Y-m-d') : null,
            'scheduledAt' => $a->scheduled_for?->format('Y-m-d'),
            'createdAt' => $a->created_at->format('Y-m-d'),
            'author' => [
                'id' => $author?->id,
                'name' => $author ? trim($author->first_name.' '.$author->last_name) : 'Unknown',
            ],
        ];
    }
}
