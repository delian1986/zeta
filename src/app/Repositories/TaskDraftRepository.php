<?php

namespace App\Repositories;

use App\Models\TaskDraft;
use App\Repositories\Contracts\TaskDraftRepositoryInterface;

class TaskDraftRepository implements TaskDraftRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): TaskDraft
    {
        return TaskDraft::create($attributes);
    }

    public function find(int|string $id): ?TaskDraft
    {
        return TaskDraft::query()->find($id);
    }

    public function findForUpdate(int|string $id): ?TaskDraft
    {
        return TaskDraft::query()->lockForUpdate()->find($id);
    }

    public function transitionStatus(TaskDraft $draft, string $to, ?int $reviewerId): void
    {
        $draft->update([
            'status' => $to,
            'reviewed_at' => now(),
            'reviewed_by_user_id' => $reviewerId,
        ]);
    }
}
