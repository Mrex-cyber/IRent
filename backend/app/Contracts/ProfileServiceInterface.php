<?php

namespace App\Contracts;

use App\Models\User;

interface ProfileServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getProfileData(User $user): array;

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateProfile(User $user, array $data): void;
}
