<?php

namespace App\Http\Controllers\Api\Game;

use App\Http\Requests\Game\Move\StoreCardMoveRequest;
use App\Services\Game\GameService;
use Illuminate\Http\JsonResponse;

class CreateMoveController
{
    public function __construct(
        private readonly GameService $service
    ) {
    }

    public function __invoke(StoreCardMoveRequest $request): JsonResponse
    {
        $this->service->createMove(
            userId: $request->user('sanctum')->id,
            dto: $request->getDto()
        );

        return response()->json();
    }
}
