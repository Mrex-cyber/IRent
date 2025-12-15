<?php

namespace App\Services;

use App\Mail\EmailVerification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegistrationService
{
    public function registerUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $token = Str::random(60);
            $user = User::create([
                'first_name' => $data['firstName'],
                'last_name' => $data['lastName'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'verification_token' => $token,
                'is_active' => false,
            ]);

            $role = Role::where('name', $data['role'])->firstOrFail();
            $user->roles()->attach($role);

            $frontendUrl = config('app.frontend_url', 'http://localhost:3001');
            $verificationUrl = "{$frontendUrl}/verify-email?token={$token}";
            Mail::to($user)->send(new EmailVerification($user, $verificationUrl));

            // Load roles relationship for response
            $user->load('roles');

            return $user;
        });

    }
}
