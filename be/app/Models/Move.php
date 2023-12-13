<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Move extends Model
{
    use HasFactory;

    protected $fillable = ['player_id', 'round_id', 'deck_card_id', 'points'];

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function deckCard(): BelongsTo
    {
        return $this->belongsTo(DeckCard::class);
    }
}
