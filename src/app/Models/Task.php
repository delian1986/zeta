<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable([
    'email_id',
    'ai_suggestion_id',
    'title',
    'description',
    'status',
    'priority',
])]
class Task extends Model
{
    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }

    public function aiSuggestion(): BelongsTo
    {
        return $this->belongsTo(AiSuggestion::class);
    }

    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
