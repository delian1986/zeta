<?php

namespace App\Services;

use App\Exceptions\TaskDraftNotApprovableException;
use App\Exceptions\TaskDraftNotFoundException;
use App\Models\Task;
use App\Models\TaskDraft;
use App\Repositories\Contracts\TaskDraftRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Support\Auditing\ActorResolver;
use Illuminate\Support\Facades\DB;

class TaskDraftService
{
    public function __construct(
        private readonly TaskDraftRepositoryInterface $taskDraftRepository,
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly ActorResolver $actor,
    ) {}

    public function findById(int|string $id): ?TaskDraft
    {
        return $this->taskDraftRepository->find($id);
    }

    public function approve(int|string $id): Task
    {
        return DB::transaction(function () use ($id) {
            $draft = $this->taskDraftRepository->findForUpdate($id);

            if ($draft === null) {
                throw new TaskDraftNotFoundException("Task draft {$id} not found.");
            }

            if ($draft->status !== 'pending_review') {
                throw new TaskDraftNotApprovableException(
                    "Task draft {$id} is not pending review (status: {$draft->status})."
                );
            }

            if ($this->taskRepository->existsForDraft($draft->id)) {
                throw new TaskDraftNotApprovableException(
                    "Task draft {$id} already has an approved task."
                );
            }

            $this->taskDraftRepository->markApproved($draft, $this->currentUserId());

            return $this->taskRepository->create([
                'email_id' => $draft->email_id,
                'task_draft_id' => $draft->id,
                'title' => $draft->title,
                'description' => $draft->summary,
                'status' => 'open',
                'priority' => $draft->priority,
            ]);
        });
    }

    private function currentUserId(): ?int
    {
        $resolved = $this->actor->resolve();
        $id = $resolved['actor_id'] ?? null;

        return is_numeric($id) ? (int) $id : null;
    }
}
