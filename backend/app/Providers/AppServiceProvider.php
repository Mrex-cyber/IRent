<?php

namespace App\Providers;

use App\Contracts\AnnouncementServiceInterface;
use App\Contracts\ConversationServiceInterface;
use App\Contracts\ProfileServiceInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\EloquentUserRepository;
use App\Services\AnnouncementService;
use App\Services\ConversationService;
use App\Services\ProfileService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AnnouncementServiceInterface::class, AnnouncementService::class);
        $this->app->bind(ConversationServiceInterface::class, ConversationService::class);
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }

    public function boot(): void
    {
        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->isProduction()
                ? $rule->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                : $rule;
        });
    }
}
