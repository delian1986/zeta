<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Task
    {
        return Task::create($attributes);
    }

    public function existsForDraft(int|string $taskDraftId): bool
    {
        return Task::query()->where('task_draft_id', $taskDraftId)->exists();
    }
}
