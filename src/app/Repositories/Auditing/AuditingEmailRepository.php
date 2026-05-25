<?php

namespace App\Repositories\Auditing;

use App\Models\Email;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Contracts\EmailRepositoryInterface;
use App\Support\Auditing\ActorResolver;

final class AuditingEmailRepository implements EmailRepositoryInterface
{
    public function __construct(
        private readonly EmailRepositoryInterface $inner,
        private readonly AuditLogRepositoryInterface $audit,
        private readonly ActorResolver $actor,
    ) {}

    public function findOrFail(int $id): Email
    {
        return $this->inner->findOrFail($id);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Email
    {
        $email = $this->inner->create($attributes);

        $this->audit->log($email, [
            'action' => 'email.created',
            'old_values' => null,
            'new_values' => $email->only(['sender', 'subject', 'status']),
            ...$this->actor->resolve(),
        ]);

        return $email;
    }

    public function updateStatus(Email $email, string $status): void
    {
        $previous = $email->status;

        $this->inner->updateStatus($email, $status);

        if ($previous === $status) {
            return;
        }

        $this->audit->log($email, [
            'action' => 'email.status_changed',
            'old_values' => ['status' => $previous],
            'new_values' => ['status' => $status],
            ...$this->actor->resolve(),
        ]);
    }
}
