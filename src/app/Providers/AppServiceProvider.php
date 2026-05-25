<?php

namespace App\Providers;

use App\Repositories\Contracts\EmailRepositoryInterface;
use App\Repositories\EmailRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            EmailRepositoryInterface::class,
            EmailRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
