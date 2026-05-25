<?php

namespace App\Repositories;

use App\Models\Email;
use App\Repositories\Contracts\EmailRepositoryInterface;

class EmailRepository implements EmailRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Email
    {
        return Email::create($attributes);
    }
}
