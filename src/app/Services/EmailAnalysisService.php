<?php

namespace App\Services;

use App\AI\AiClientInterface;
use App\AI\DTO\EmailAnalysisResult;
use App\Models\Email;
use App\Models\TaskDraft;
use App\Repositories\Contracts\EmailRepositoryInterface;
use App\Repositories\Contracts\TaskDraftRepositoryInterface;
use App\Services\DTO\EmailAnalysisOutcome;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailAnalysisService
{
    public function __construct(
        private readonly AiClientInterface $aiClient,
        private readonly EmailRepositoryInterface $emailRepository,
        private readonly TaskDraftRepositoryInterface $taskDraftRepository,
    ) {}

    public function analyze(Email $email): EmailAnalysisOutcome
    {
        $aiResult = $this->callAiAnalysis($email);

        if (! $aiResult->taskDetected) {
            return $this->handleNoTask($email);
        }

        $draft = $this->persistDetectedTaskDraft($email, $aiResult);

        // TODO:: notify user (email, slack)

        return EmailAnalysisOutcome::draftCreated($draft->id);
    }

    private function callAiAnalysis(Email $email): EmailAnalysisResult
    {
        return $this->aiClient->analyze($email);
    }

    private function handleNoTask(Email $email): EmailAnalysisOutcome
    {
        $this->emailRepository->updateStatus($email, 'ignored');

        return EmailAnalysisOutcome::ignored();
    }

    /**
     * @return array<string, mixed>
     */
    private function taskDraftAttributesFromAnalysis(Email $email, EmailAnalysisResult $result): array
    {
        return [
            'email_id' => $email->id,
            'type' => $result->type ?? 'general',
            'title' => $result->title ?? 'Draft from email',
            'summary' => $result->summary ?? Str::limit($email->body, 500),
            'priority' => $result->priority ?? 'medium',
            'suggested_project' => $result->suggestedProject,
            'suggested_team' => $result->suggestedTeam,
            'confidence' => $result->confidence ?? 80,
            'missing_information' => $result->missingInformation,
            'next_action' => $result->nextAction,
            'raw_ai_response' => $result->rawAiResponse ?? ['task_detected' => true],
            'status' => 'pending_review',
            'reviewer_notes' => null,
            'reviewed_at' => null,
            'reviewed_by_user_id' => null,
        ];
    }

    private function persistDetectedTaskDraft(Email $email, EmailAnalysisResult $aiResult): TaskDraft
    {
        return DB::transaction(function () use ($email, $aiResult) {
            $draft = $this->taskDraftRepository->create(
                $this->taskDraftAttributesFromAnalysis($email, $aiResult),
            );

            $this->emailRepository->updateStatus($email, 'draft_created');

            return $draft;
        });
    }
}
