<?php

namespace App\Repositories;

use App\Models\Email;
use App\Repositories\Contracts\EmailRepositoryInterface;

class EmailRepository implements EmailRepositoryInterface
{
    public function findOrFail(int $id): Email
    {
        return Email::query()->findOrFail($id);
    }

    public function updateStatus(Email $email, string $status): void
    {
        $email->update(['status' => $status]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Email
    {
        return Email::create($attributes);
    }
}
