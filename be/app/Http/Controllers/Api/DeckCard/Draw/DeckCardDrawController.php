<?php

namespace App\Http\Controllers\Api\DeckCard\Draw;

use App\Http\Controllers\Controller;
use App\Services\DeckCard\Draw\DeckCardDrawServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeckCardDrawController extends Controller
{
    public function __construct(
        private readonly DeckCardDrawServiceInterface $service
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->service->getDrawCard($request->user('sanctum')->id);

        return response()->json();
    }
}
