<?php

namespace App\Services;

use App\Contracts\AnnouncementServiceInterface;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Collection;

class AnnouncementService implements AnnouncementServiceInterface
{
    /**
     * @return Collection<int, Announcement>
     */
    public function listForUser(User $user, ?string $category = null, ?string $searchFieldText = null): Collection
    {
        $query = Announcement::with('author')
            ->where('user_id', $user->id);

        if ($category !== null && $category !== '' && strcasecmp($category, 'All') !== 0) {
            $query->where('type', strtolower($category));
        }

        $term = $searchFieldText !== null ? trim($searchFieldText) : '';
        if ($term !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $query->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                    ->orWhere('content', 'like', $like);
            });
        }

        return $query->latest()->get();
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
}
