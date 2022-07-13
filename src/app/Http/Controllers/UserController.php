<?php

namespace App\Http\Controllers;

use App\Http\Resources\PhotoResource;
use App\Http\Resources\UserResource;
use App\Models\Phone;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
     * Show user info
     *
     * @param int|null $id
     */
    public function show(?int $id = null)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($id === null) {
            $id = $user->id;
        }

        if ($id != $user->id) {
            // Check is user in contact book
            if (!$this->repository->isFriend($user->id, $id)) {
                return $this->respondData([
                    'error' => "User not in your contact book"
                ], Response::HTTP_FORBIDDEN);
            }
        }

        /** @var User $user */
        $user = $this->repository->find($id)->load([
            'photos',
        ]);

        return new UserResource($user);
    }

    /**
     * @param Request $request
     * @return PhotoResource|JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function uploadAvatar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $validator->validate();

        /** @var User $user */
        $user = auth()->user();
        /** @var UploadedFile $image */
        $image = $request->post('image');

        $photo = $this->repository->storePhoto($user->id, $image);

        return new PhotoResource($photo);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        // TODO: finish validation rules
        $validator = Validator::make($request->all(), [
            'firstname' => '',
            'lastname' => '',
            'lang' => '',
            'twitter' => '',
            'github' => '',
            'instagram' => '',
            'reddit' => '',
            'facebook' => '',
            'telegram' => '',
        ]);

        $validator->validate();

        /** @var User $user */
        $user = auth()->user();

        $user->update($validator->validated());

        return $this->respondData();
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changePhone(Request $request)
    {
        // TODO: finish validation rules
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        $validator->validate();

        /** @var User $user */
        $user = auth()->user();
        $phone = $request->input('phone');

        $user->phones()->update(['is_main' => false]);
        /** @var Phone $userPhone */
        $userPhone = $user->phones()->firstOrNew(['phone' => $phone]);
        $userPhone->is_main = true;
        $userPhone->save();

        return $this->respondData($user->getAuthCredentials());
    }
}
