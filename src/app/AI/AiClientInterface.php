<?php

namespace App\AI;

use App\AI\DTO\EmailAnalysisResult;
use App\Models\Email;

interface AiClientInterface
{
    public function analyze(Email $email): EmailAnalysisResult;
}
