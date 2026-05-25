<?php

namespace App\Repositories\Contracts;

use App\Models\AuditLog;
use App\Models\Email;

interface AuditLogRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createForEmail(Email $email, array $attributes): AuditLog;
}
