<?php

namespace App\Repositories;

use App\Models\AuditLog;
use App\Models\Email;
use App\Repositories\Contracts\AuditLogRepositoryInterface;

class AuditLogRepository implements AuditLogRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createForEmail(Email $email, array $attributes): AuditLog
    {
        return $email->auditLogs()->create($attributes);
    }
}
