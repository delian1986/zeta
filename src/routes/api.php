<?php

use App\Http\Controllers\EmailController;
use App\Http\Controllers\TaskDraftController;
use Illuminate\Support\Facades\Route;

Route::post("/incoming-emails", [EmailController::class, "store"]);

Route::get('task-drafts/{id}', [TaskDraftController::class, 'show']);
Route::post('task-drafts/{id}/approve', [TaskDraftController::class, 'approve']);
Route::post('task-drafts/{id}/reject', [TaskDraftController::class, 'reject']);
Route::post('task-drafts/{id}/override', [TaskDraftController::class, 'override']);
