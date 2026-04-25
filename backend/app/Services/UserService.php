<?php

namespace App\Services;

use App\Contracts\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * @return Collection<int, \App\Models\User>
     */
    public function listActiveForSelect(): Collection
    {
        return $this->userRepository->getActiveOrderedByName();
    }
}
