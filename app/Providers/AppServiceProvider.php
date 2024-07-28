<?php

namespace App\Providers;

use App\Models\UserChangeRequest;
use App\Policies\RoleChangeRequestPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(UserChangeRequest::class, RoleChangeRequestPolicy::class);
    }
}
