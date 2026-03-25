<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

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

        Gate::define('user', function ($user = null) {
            $auth = Auth::guard('user')->check();
            return $auth && Auth::guard('user')->user()->admin_role == 1;
        });

        Gate::define('admin', function ($user = null) {
            $auth = Auth::guard('admin')->check();
            return $auth && Auth::guard('admin')->user()->admin_role == 2;
        });

        Gate::define('administrator', function ($user = null) {
            $auth = Auth::guard('admin')->check();
            return $auth && Auth::guard('admin')->user()->admin_role == 7;
        });
    }
}
