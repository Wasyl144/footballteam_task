<?php

namespace App\Http\Resources\Deck\Card;

use App\Models\DeckCard;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DeckCard
 */
class DeckCardResource extends JsonResource
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
            'name' => $this->card->name,
            'image' => $this->card->image,
            'power' => $this->card->power,
        ];
    }
}
