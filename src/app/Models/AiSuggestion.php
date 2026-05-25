<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable([
    'email_id',
    'type',
    'title',
    'summary',
    'priority',
    'suggested_project',
    'suggested_team',
    'confidence',
    'missing_information',
    'next_action',
    'raw_ai_response',
    'status',
    'reviewer_notes',
    'reviewed_at',
    'reviewed_by_user_id',
])]
class AiSuggestion extends Model
{
    protected function casts(): array
    {
        return [
            'raw_ai_response' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
