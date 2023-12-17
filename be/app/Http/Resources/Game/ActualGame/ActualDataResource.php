<?php

namespace App\Http\Resources\Game\ActualGame;

use App\Dtos\Game\ActualInfo\ActualGameInfoDto;
use App\Http\Resources\Deck\Card\DeckCardResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ActualGameInfoDto $resource
 */
class ActualDataResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'round' => $this->resource->round,
            'your_points' => $this->resource->yourPoints,
            'opponent_points' => $this->resource->opponentPoints,
            'status' => $this->resource->status,
            'cards' => DeckCardResource::collection($this->resource->cards),
        ];
    }
}
