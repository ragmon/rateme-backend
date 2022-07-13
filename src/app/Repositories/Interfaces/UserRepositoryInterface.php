<?php

namespace App\Repositories\Interfaces;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

interface UserRepositoryInterface
{
    public function find(int $id): User;
    public function isFriend(int $firstUserId, int $secondUserId): bool;
    public function storePhoto(int $userId, UploadedFile $file): Photo;
    public function createUser(string $phone);
    public function findByPhone(string $phone);
    public function updateLoginAt(int $userID);
}
