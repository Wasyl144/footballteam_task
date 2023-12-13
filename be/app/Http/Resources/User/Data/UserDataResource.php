<?php

namespace App\Http\Resources\User\Data;

use App\Http\Resources\Deck\Card\DeckCardResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserDataResource extends JsonResource
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
            'id' => $this->id,
            'username' => $this->name,
            'level' => $this?->player?->level,
            'level_points' => '40/100',
            'cards' => DeckCardResource::collection($this->player->deck->deckCards),
            'new_card_allowed' => true,
        ];
    }
}
