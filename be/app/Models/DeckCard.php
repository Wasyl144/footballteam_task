<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\DeckCard
 *
 * @property int $id
 * @property int $card_id
 * @property int $deck_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Card $card
 * @property-read \App\Models\Deck $deck
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Move> $moves
 * @property-read int|null $moves_count
 *
 * @method static \Database\Factories\DeckCardFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|DeckCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeckCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DeckCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|DeckCard whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeckCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeckCard whereDeckId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeckCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DeckCard whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class DeckCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'deck_id',
        'card_id',
    ];

    public function deck(): BelongsTo
    {
        return $this->belongsTo(Deck::class);
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function moves(): HasMany
    {
        return $this->hasMany(Move::class);
    }
}
