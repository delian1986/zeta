<?php

namespace App\Jobs;

use App\Repositories\Contracts\EmailRepositoryInterface;
use App\Services\EmailAnalysisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class AnalyzeEmailJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public int $emailId,
    ) {}

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function handle(EmailAnalysisService $analysisService, EmailRepositoryInterface $emailRepository): void
    {
        $email = $emailRepository->findOrFail($this->emailId);
        $analysisService->analyze($email);
    }

    public function failed(?Throwable $e = null): void
    {
        $repository = app(EmailRepositoryInterface::class);

        try {
            $email = $repository->findOrFail($this->emailId);
        } catch (Throwable) {
            return;
        }

        if (in_array($email->status, ['draft_created', 'ignored', 'failed'], true)) {
            return;
        }

        $repository->updateStatus($email, 'failed');
    }
}
