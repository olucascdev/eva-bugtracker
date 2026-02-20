<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected function getRedirectUrl(): string
    {
        $user = $this->getGuard()->user();

        if (! $user) {
            return '/login';
        }

        if ($user->isAdmin() || $user->isSupport()) {
            return '/eva';
        }

        return '/client';
    }
}
