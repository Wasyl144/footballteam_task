<?php

namespace App\Services\User\Data;

use App\Dtos\User\Data\UserDataDto;

interface UserDataServiceInterface
{
    public function getUserData(int $id): UserDataDto;
}
