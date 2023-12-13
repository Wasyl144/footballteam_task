<?php

namespace App\Http\Controllers\Api\User\Data;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\Data\UserDataResource;
use Illuminate\Http\Request;

class UserDataController extends Controller
{
    public function index(Request $request): UserDataResource
    {
        return UserDataResource::make($request->user('sanctum'));
    }
}
