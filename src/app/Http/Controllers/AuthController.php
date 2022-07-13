<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;

    private UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required'
        ]);

        // TODO: возможно оптимизировать используя validate() и вызовом обработчика поумолчанию (app/Exceptions/Handler.php)
        if ($validator->fails()) {
            return $this->respondData([
                'error' => $validator->errors()->all(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $phone = $request->input('phone');

        /** @var User $user */
        if ( ! $user = $this->repository->findByPhone($phone)) {
            $user = $this->repository->createUser($phone);
            return $this->respondData($user->getAuthCredentials(), Response::HTTP_CREATED);
        }

        $this->repository->updateLoginAt($user->id);

        return $this->respondData($user->getAuthCredentials());
    }
}
