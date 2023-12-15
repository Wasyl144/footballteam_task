<?php

namespace App\Http\Resources\Duels;

use App\Dtos\Duels\DuelsHistoryDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property DuelsHistoryDto $resource
 */
class DuelsHistoryResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'player_name' => $this->resource->playerName,
            'opponent_name' => $this->resource->opponentName,
            'won' => $this->resource->won,
        ];
    }
}
