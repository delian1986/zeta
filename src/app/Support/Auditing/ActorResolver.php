<?php

namespace App\Support\Auditing;

use Illuminate\Contracts\Auth\Factory as AuthFactory;

final class ActorResolver
{
    public function __construct(
        private readonly AuthFactory $auth,
    ) {}

    /**
     * @return array{actor_type: string, actor_id: int|string|null}
     */
    public function resolve(): array
    {
        $user = $this->auth->guard()->user();

        if ($user === null) {
            return [
                'actor_type' => 'system',
                'actor_id' => null,
            ];
        }

        return [
            'actor_type' => $user::class,
            'actor_id' => $user->getAuthIdentifier(),
        ];
    }
}
