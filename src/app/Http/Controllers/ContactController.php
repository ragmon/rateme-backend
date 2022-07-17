<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Builder;

class ContactController extends Controller
{
    use ApiResponse;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        $phones = $user->contacts()->with(['photo', 'phones'])->get()
            ->pluck('phones.*.phone')
            ->flatten();

        $users = $this->repository->listByPhones($phones);
        $users->loadCount(['rates', 'rated']);

        return $this->respondData($users);
    }
}
