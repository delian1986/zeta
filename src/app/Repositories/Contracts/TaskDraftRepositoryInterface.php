<?php

namespace App\Repositories\Contracts;

use App\Models\TaskDraft;

interface TaskDraftRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): TaskDraft;

    public function find(int|string $id): ?TaskDraft;

    public function findForUpdate(int|string $id): ?TaskDraft;

    public function markApproved(TaskDraft $draft, ?int $reviewerId): void;
}
