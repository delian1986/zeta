<?php

namespace App\Repositories\Contracts;

use App\Models\Email;

interface EmailRepositoryInterface
{
    public function findOrFail(int $id): Email;

    public function updateStatus(Email $email, string $status): void;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Email;
}
