<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\BlogPost::class => \App\Policies\BlogPostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('post.edit', 'App\Policies\BlogPostPolicy@update');
        Gate::define('post.delete', 'App\Policies\BlogPostPolicy@delete');

        Gate::before(function ($user, $ability) {
            if ($user->is_admin && in_array($ability, ['post-edit', 'post-delete'])) {
                return true;
            }
        });
    }
}
