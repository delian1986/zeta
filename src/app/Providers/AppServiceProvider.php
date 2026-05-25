<?php

namespace App\Providers;

use App\AI\AiClientInterface;
use App\AI\MockAiClient;
use App\AI\OpenAiClient;
use App\Repositories\AuditLogRepository;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Contracts\EmailRepositoryInterface;
use App\Repositories\Contracts\TaskDraftRepositoryInterface;
use App\Repositories\EmailRepository;
use App\Repositories\TaskDraftRepository;
use Illuminate\Contracts\Foundation\Application;
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

        $this->app->bind(
            TaskDraftRepositoryInterface::class,
            TaskDraftRepository::class,
        );

        $this->app->bind(
            AuditLogRepositoryInterface::class,
            AuditLogRepository::class,
        );

        $this->app->bind(AiClientInterface::class, function (Application $app) {
            return match (config('ai.client')) {
                'openai' => $app->make(OpenAiClient::class),
                default => $app->make(MockAiClient::class),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
