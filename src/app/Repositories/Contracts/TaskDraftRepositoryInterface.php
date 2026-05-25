<?php

namespace App\Repositories\Contracts;

use App\Models\TaskDraft;

interface TaskDraftRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): TaskDraft;
}
