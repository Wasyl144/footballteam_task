<?php

namespace App\Http\Resources\User\Data;

use App\Dtos\User\Data\UserDataDto;
use App\Http\Resources\Deck\Card\DeckCardResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property UserDataDto $resource
 */
class UserDataResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'username' => $this->resource->name,
            'level' => $this->resource->level,
            'level_points' => $this->resource->levelPoints,
            'cards' => DeckCardResource::collection($this->resource->cards),
            'new_card_allowed' => $this->resource->isNewCardAllowed,
        ];
    }
}
