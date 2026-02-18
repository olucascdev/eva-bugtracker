<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToProperPanelMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Filament::auth()->check()) {
            return $next($request);
        }

        $user = Filament::auth()->user();
        $panel = Filament::getCurrentPanel();

        if (! $panel) {
            return $next($request);
        }

        $panelId = $panel->getId();

        if ($panelId === 'eva') {
            if ($user->isClient()) {
                return redirect()->to(Filament::getPanel('client')->getUrl());
            }
        } elseif ($panelId === 'client') {
            if ($user->isAdmin() || $user->isSupport()) {
                return redirect()->to(Filament::getPanel('eva')->getUrl());
            }
        }

        return $next($request);
    }
}
