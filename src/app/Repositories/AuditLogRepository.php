<?php

namespace App\Repositories;

use App\Models\AuditLog;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class AuditLogRepository implements AuditLogRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function log(Model $auditable, array $attributes): AuditLog
    {
        return $auditable->auditLogs()->create($attributes);
    }
}
