<?php

namespace App\Http\Controllers\Api\Game;

use App\Http\Controllers\Controller;
use App\Http\Resources\Game\ActualGame\ActualDataResource;
use App\Services\Game\GameServiceInterface;
use Illuminate\Http\Request;

class ActualGameDataController extends Controller
{
    public function __construct(
        private readonly GameServiceInterface $service
    ) {
    }

    public function __invoke(Request $request): ActualDataResource
    {
        $result = $this->service->actualGameInfo($request->user('sanctum')->id);

        return ActualDataResource::make($result);
    }
}
