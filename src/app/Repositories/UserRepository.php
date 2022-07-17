<?php

namespace App\Repositories;

use App\Models\Phone;
use App\Models\Photo;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * Find user with ID
     *
     * @param int $id
     * @return User
     */
    public function find(int $id): User
    {
        return User::where('id', $id)->firstOrFail();
    }

    /**
     * Checking is two users in mutual contact list
     *
     * @param int $firstUserId
     * @param int $secondUserId
     * @return bool
     */
    public function isFriend(int $firstUserId, int $secondUserId): bool
    {
        /** @var User $firstUser */
        $firstUser = User::where('id', $firstUserId)->firstOrFail();
        /** @var User $secondUser */
        $secondUser = User::where('id', $secondUserId)->firstOrFail();

        $firstUserPhones = $firstUser->phones->pluck('phone');
        $secondUserPhones = $secondUser->phones->pluck('phone');

        $firstUserHasContact = $secondUser->whereHas('contacts.phones', function (Builder $query) use ($secondUserPhones) {
            $query->whereIn('phone', $secondUserPhones);
        })->exists();

        $secondUserHasContact = $firstUser->whereHas('contacts.phones', function (Builder $query) use ($firstUserPhones) {
            $query->whereIn('phone', $firstUserPhones);
        })->exists();

        return $firstUserHasContact && $secondUserHasContact;
    }

    public function storePhoto(int $userId, UploadedFile $file): Photo
    {
        $imageName = Str::random() . '.' . $file->extension();
        $fileDir = storage_path('photos');
        $path = $fileDir . '/' . $imageName;
        $image = Image::make($file->path());
        $image->resize(300, 300, function ($const) {
            $const->aspectRatio();
        })->save($path);
        // TODO: provide to public folder

        $photo = new Photo();
        $photo->path = $imageName;
        $photo->driver = 'local';
        $photo->is_main = true;

        $user = User::where('id', $userId)->firstOrFail();
        $user->photos()->delete();
        $user->photos()->save($photo);

        return $photo;
    }

    public function createUser(string $phone)
    {
        $user = new User();
        $user->save();

        $phoneModel = new Phone();
        $phoneModel->phone = $phone;
        $phoneModel->save();

        $user->phones()->save($phoneModel);

        return $user;
    }

    public function findByPhone(string $phone)
    {
        return User::whereHas('phones', function (Builder $query) use ($phone) {
            $query->where('phone', $phone);
        })->first();
    }

    public function updateLoginAt(int $userID)
    {
        User::where('id', $userID)->update([
            // TODO: finally SQL `now()` or `Carbon::now()`?
            'login_at' => Carbon::now()
        ]);
    }

    public function listByPhones(iterable $phones)
    {
        return User::whereHas('phones', function (Builder $query) use ($phones) {
            $query->whereIn('phone', $phones);
        })->get();
    }
}

