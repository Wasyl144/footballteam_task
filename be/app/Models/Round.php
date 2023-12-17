<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Round
 *
 * @property int $id
 * @property int $game_id
 * @property int $round_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Move> $moves
 * @property-read int|null $moves_count
 *
 * @method static \Database\Factories\RoundFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Round newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Round newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Round query()
 * @method static \Illuminate\Database\Eloquent\Builder|Round whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Round whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Round whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Round whereRoundNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Round whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Round extends Model
{
    use HasFactory;

    protected $fillable = ['game_id', 'round_number'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function moves(): HasMany
    {
        return $this->hasMany(Move::class);
    }
}
