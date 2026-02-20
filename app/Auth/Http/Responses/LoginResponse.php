<?php

namespace App\Auth\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements Responsable
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = auth()->user();

        if ($user?->isAdmin() || $user?->isSupport()) {
            return redirect('/eva');
        }

        if ($user?->isClient()) {
            return redirect('/client');
        }

        return redirect('/login');
    }
}
