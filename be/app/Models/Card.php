<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Card
 *
 * @property int $id
 * @property string $name
 * @property int $power
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeckCard> $deckCards
 * @property-read int|null $deck_cards_count
 *
 * @method static \Database\Factories\CardFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Card query()
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card wherePower($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Card withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Card withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Card extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'power', 'image'];

    public function deckCards(): HasMany
    {
        return $this->hasMany(DeckCard::class);
    }
}
