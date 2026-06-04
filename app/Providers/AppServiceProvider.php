<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use App\Models\Post;
use App\Policies\PostPolicy;
use App\Models\User;

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
        //
        Paginator::useBootstrapFive();

        Gate::policy(Post::class, PostPolicy::class);

        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });

        Gate::before(function (User $user, string $ability) {
            if ($user->hasPermissionTo($ability)) {
                return true;
            }
        });
    }
}
