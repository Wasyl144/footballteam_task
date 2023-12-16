<?php

namespace App\Http\Resources\Deck\Card;

use App\Models\DeckCard;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property DeckCard $resource
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
            'id' => $this->resource->id,
            'name' => $this->resource->card->name,
            'image' => $this->resource->card->image,
            'power' => $this->resource->card->power,
        ];
    }
}
