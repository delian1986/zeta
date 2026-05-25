<?php

namespace App\AI\DTO;

final readonly class EmailAnalysisResult
{
    /**
     * @param  array<string, mixed>|null  $rawAiResponse
     */
    public function __construct(
        public bool $taskDetected,
        public ?string $type = null,
        public ?string $title = null,
        public ?string $summary = null,
        public ?string $priority = null,
        public ?string $suggestedProject = null,
        public ?string $suggestedTeam = null,
        public ?int $confidence = null,
        public ?string $missingInformation = null,
        public ?string $nextAction = null,
        public ?array $rawAiResponse = null,
    ) {}
}
