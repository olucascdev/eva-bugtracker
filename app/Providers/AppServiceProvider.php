<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Filament\Auth\Http\Responses\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
        $this->app->singleton(
            \Filament\Auth\Http\Responses\Contracts\LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::policy(\App\Models\User::class, \App\Policies\UserPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Bug::class, \App\Policies\BugPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Company::class, \App\Policies\CompanyPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Role::class, \App\Policies\RolePolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\BugPriority::class, \App\Policies\BugPriorityPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\BugStatus::class, \App\Policies\BugStatusPolicy::class);

        \App\Models\Bug::observe(\App\Observers\BugObserver::class);
    }
}
