<?php

namespace App\AI;

use App\AI\DTO\EmailAnalysisResult;
use App\AI\Exceptions\AiAnalysisException;
use App\Models\Email;

class OpenAiClient implements AiClientInterface
{
    public function analyze(Email $email): EmailAnalysisResult
    {
        throw new AiAnalysisException('OpenAI client is not implemented.');
    }
}
