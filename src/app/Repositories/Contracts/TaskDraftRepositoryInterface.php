<?php

namespace App\Repositories\Contracts;

use App\Models\TaskDraft;

interface TaskDraftRepositoryInterface
{
    public function create(array $attributes): TaskDraft;

    public function find(int|string $id): ?TaskDraft;

    public function findForUpdate(int|string $id): ?TaskDraft;

    public function transitionStatus(TaskDraft $draft, string $to, ?int $reviewerId): void;

    public function override(TaskDraft $draft, array $attributes): void;
}
