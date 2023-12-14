<?php

namespace App\Http\Controllers\Api\User\Data;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\Data\UserDataResource;
use App\Services\User\Data\UserDataServiceInterface;
use Illuminate\Http\Request;

class GetUserDataController extends Controller
{
    public function __construct(
        private readonly UserDataServiceInterface $service
    )
    {
    }

    public function __invoke(Request $request): UserDataResource
    {
        return UserDataResource::make(
            $this->service->getUserData($request->user('sanctum')->id)
        );
    }
}
