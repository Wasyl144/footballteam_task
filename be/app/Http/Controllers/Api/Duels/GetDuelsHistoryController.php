<?php

namespace App\Http\Controllers\Api\Duels;

use App\Http\Resources\Duels\DuelsHistoryResource;
use App\Services\Duels\DuelsServiceInterface;
use Illuminate\Http\Request;

class GetDuelsHistoryController
{
    public function __construct(
        private readonly DuelsServiceInterface $service
    ) {
    }

    public function __invoke(Request $request): array
    {
        return DuelsHistoryResource::collection(
            $this->service->getDuelsHistoryByUser($request->user('sanctum')->id)
        )->all();
    }
}
