<?php

namespace App\Services;

use App\Jobs\AnalyzeEmailJob;
use App\Models\Email;
use App\Repositories\Contracts\EmailRepositoryInterface;

class EmailIngestionService
{
    public function __construct(
        private readonly EmailRepositoryInterface $emailRepository,
    ) {}

    public function storeIncoming(string $from, string $subject, string $body): Email
    {
        $email = $this->emailRepository->create([
            "sender" => $from,
            "subject" => $subject,
            "body" => $body,
            "status" => "pending", // TODO:: statuses should be ENUM
        ]);

        AnalyzeEmailJob::dispatch($email->id);

        return $email;
    }
}
