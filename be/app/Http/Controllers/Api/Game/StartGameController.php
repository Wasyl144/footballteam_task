<?php

namespace App\Http\Controllers\Api\Game;

use App\Services\Game\GameServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StartGameController
{
    public function __construct(
        private readonly GameServiceInterface $service
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->service->createGame($request->user('sanctum')->id);

        return response()->json();
    }
}
