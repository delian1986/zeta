<?php

namespace App\Repositories\Contracts;

use App\Models\Email;

interface EmailRepositoryInterface
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Email;
}
