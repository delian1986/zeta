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
}
