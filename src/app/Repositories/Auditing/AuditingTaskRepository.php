<?php

namespace App\Repositories\Auditing;

use App\Models\Task;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Support\Auditing\ActorResolver;

final class AuditingTaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        private readonly TaskRepositoryInterface $inner,
        private readonly AuditLogRepositoryInterface $audit,
        private readonly ActorResolver $actor,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Task
    {
        $task = $this->inner->create($attributes);

        $this->audit->log($task, [
            'action' => 'task.created',
            'old_values' => null,
            'new_values' => $task->only(['id', 'email_id', 'task_draft_id', 'status', 'title']),
            ...$this->actor->resolve(),
        ]);

        return $task;
    }

    public function existsForDraft(int|string $taskDraftId): bool
    {
        return $this->inner->existsForDraft($taskDraftId);
    }
}
