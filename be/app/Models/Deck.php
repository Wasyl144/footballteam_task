<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Deck extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
    ];

    public function player(): HasOne
    {
        return $this->hasOne(Player::class);
    }

    public function deckCards(): HasMany
    {
        return $this->hasMany(DeckCard::class);
    }
}
