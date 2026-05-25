<?php

namespace App\Repositories\Auditing;

use App\Models\TaskDraft;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Contracts\TaskDraftRepositoryInterface;
use App\Support\Auditing\ActorResolver;

final class AuditingTaskDraftRepository implements TaskDraftRepositoryInterface
{
    public function __construct(
        private readonly TaskDraftRepositoryInterface $inner,
        private readonly AuditLogRepositoryInterface $audit,
        private readonly ActorResolver $actor,
    ) {}

    public function find(int|string $id): ?TaskDraft
    {
        return $this->inner->find($id);
    }

    public function findForUpdate(int|string $id): ?TaskDraft
    {
        return $this->inner->findForUpdate($id);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): TaskDraft
    {
        $draft = $this->inner->create($attributes);

        $this->audit->log($draft, [
            'action' => 'task_draft.created',
            'old_values' => null,
            'new_values' => $draft->only(['id', 'email_id', 'status', 'title']),
            ...$this->actor->resolve(),
        ]);

        return $draft;
    }

    public function markApproved(TaskDraft $draft, ?int $reviewerId): void
    {
        $previousStatus = $draft->status;

        $this->inner->markApproved($draft, $reviewerId);

        $this->audit->log($draft, [
            'action' => 'task_draft.approved',
            'old_values' => ['status' => $previousStatus],
            'new_values' => [
                'status' => 'approved',
                'reviewed_by_user_id' => $reviewerId,
            ],
            ...$this->actor->resolve(),
        ]);
    }
}
