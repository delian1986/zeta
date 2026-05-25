<?php

namespace App\AI;

use App\AI\DTO\EmailAnalysisResult;
use App\Models\Email;
use Illuminate\Support\Str;

class MockAiClient implements AiClientInterface
{
    public function analyze(Email $email): EmailAnalysisResult
    {
        return new EmailAnalysisResult(
            taskDetected: true,
            type: 'general',
            title: 'Draft from email',
            summary: Str::limit($email->body, 500),
            priority: 'medium',
            suggestedProject: null,
            suggestedTeam: null,
            confidence: 80,
            missingInformation: null,
            nextAction: null,
            rawAiResponse: ['task_detected' => true],
        );
    }
}
