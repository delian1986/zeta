<?php

namespace App\Http\Controllers;

use App\Exceptions\TaskDraftNotApprovableException;
use App\Exceptions\TaskDraftNotFoundException;
use App\Exceptions\TaskDraftNotOverridableException;
use App\Exceptions\TaskDraftNotRejectableException;
use App\Http\Requests\ApproveTaskDraftRequest;
use App\Http\Requests\OverrideTaskDraftRequest;
use App\Http\Requests\RejectTaskDraftRequest;
use App\Http\Requests\TaskDraftRequest;
use App\Services\TaskDraftService;
use Illuminate\Http\JsonResponse;

class TaskDraftController extends Controller
{
    public function __construct(
        private readonly TaskDraftService $taskDraftService,
    ) {}

    public function show(TaskDraftRequest $request): JsonResponse
    {
        $draft = $this->taskDraftService->findById($request->validated('id'));

        if ($draft === null) {
            abort(404);
        }

        return response()->json($draft);
    }

    public function approve(ApproveTaskDraftRequest $request): JsonResponse
    {
        try {
            $task = $this->taskDraftService->approve($request->validated('id'));
        } catch (TaskDraftNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (TaskDraftNotApprovableException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }

        return response()->json($task, 201);
    }

    public function reject(RejectTaskDraftRequest $request): JsonResponse
    {
        try {
            $draft = $this->taskDraftService->reject($request->validated('id'));
        } catch (TaskDraftNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (TaskDraftNotRejectableException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }

        return response()->json($draft, 200);
    }

    public function override(OverrideTaskDraftRequest $request): JsonResponse
    {
        try {
            $draft = $this->taskDraftService->override(
                $request->validated('id'),
                $request->overrides(),
            );
        } catch (TaskDraftNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (TaskDraftNotOverridableException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }

        return response()->json($draft, 200);
    }
}
