<?php

namespace App\Contracts;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Collection;

interface AnnouncementServiceInterface
{
    /**
     * @return Collection<int, Announcement>
     */
    public function listForUser(User $user): Collection;

    public function create(User $user, array $data): Announcement;

    public function update(Announcement $announcement, User $user, array $data): Announcement;

    public function delete(Announcement $announcement, User $user): void;
}
