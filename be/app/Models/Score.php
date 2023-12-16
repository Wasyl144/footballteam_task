<?php

namespace App\Models;

use App\Enums\Score\ScoreStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Score
 *
 * @property int $id
 * @property int $player_id
 * @property int $game_id
 * @property ScoreStatusEnum $status
 * @property int $points
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Game $game
 * @property-read \App\Models\Player $player
 *
 * @method static \Database\Factories\ScoreFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Score newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Score newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Score query()
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Score whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Score extends Model
{
    use HasFactory;

    protected $fillable = ['player_id', 'status', 'game_id', 'points'];

    protected $casts = [
        'status' => ScoreStatusEnum::class,
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
