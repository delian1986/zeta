<?php

namespace App\Providers;

use App\AI\AiClientInterface;
use App\AI\MockAiClient;
use App\AI\OpenAiClient;
use App\Repositories\AuditLogRepository;
use App\Repositories\Auditing\AuditingEmailRepository;
use App\Repositories\Auditing\AuditingTaskDraftRepository;
use App\Repositories\Auditing\AuditingTaskRepository;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Contracts\EmailRepositoryInterface;
use App\Repositories\Contracts\TaskDraftRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\EmailRepository;
use App\Repositories\TaskDraftRepository;
use App\Repositories\TaskRepository;
use App\Support\Auditing\ActorResolver;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EmailRepositoryInterface::class, function ($app) {
            return new AuditingEmailRepository(
                $app->make(EmailRepository::class),
                $app->make(AuditLogRepositoryInterface::class),
                $app->make(ActorResolver::class),
            );
        });

        $this->app->bind(TaskDraftRepositoryInterface::class, function ($app) {
            return new AuditingTaskDraftRepository(
                $app->make(TaskDraftRepository::class),
                $app->make(AuditLogRepositoryInterface::class),
                $app->make(ActorResolver::class),
            );
        });

        $this->app->bind(TaskRepositoryInterface::class, function ($app) {
            return new AuditingTaskRepository(
                $app->make(TaskRepository::class),
                $app->make(AuditLogRepositoryInterface::class),
                $app->make(ActorResolver::class),
            );
        });

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
