<?php

namespace App\Http\Controllers;

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
}
