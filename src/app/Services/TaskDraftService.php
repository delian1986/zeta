<?php

namespace App\Services;

use App\Models\TaskDraft;
use App\Repositories\Contracts\TaskDraftRepositoryInterface;

class TaskDraftService
{
    public function __construct(
        private readonly TaskDraftRepositoryInterface $taskDraftRepository,
    ) {}

    public function findById(int|string $id): ?TaskDraft
    {
        return $this->taskDraftRepository->find($id);
    }
}
