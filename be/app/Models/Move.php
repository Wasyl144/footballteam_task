<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Move
 *
 * @property int $id
 * @property int $player_id
 * @property int $round_id
 * @property int $deck_card_id
 * @property int $points
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DeckCard $deckCard
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Round $round
 *
 * @method static \Database\Factories\MoveFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Move newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Move newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Move query()
 * @method static \Illuminate\Database\Eloquent\Builder|Move whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Move whereDeckCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Move whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Move wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Move wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Move whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Move whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
