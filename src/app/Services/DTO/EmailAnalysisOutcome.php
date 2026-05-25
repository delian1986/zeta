<?php

namespace App\Services\DTO;

final readonly class EmailAnalysisOutcome
{
    private function __construct(
        public string $resolution,
        public ?int $taskDraftId = null,
    ) {}

    public static function ignored(): self
    {
        return new self('ignored', null);
    }

    public static function draftCreated(int $taskDraftId): self
    {
        return new self('draft_created', $taskDraftId);
    }
}
