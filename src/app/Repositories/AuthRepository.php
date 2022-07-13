<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{
    public function register(array $credentials)
    {
        /** @var User $user */
        $user = User::create($credentials);

        if ($credentials['phones']) {
            $user->phones()->createMany($credentials['phones']);
        }

        if ($credentials['photos']) {
            $user->photos()->createMany($credentials['photos']);
        }
    }
}
