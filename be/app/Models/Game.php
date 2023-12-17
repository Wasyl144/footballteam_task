<?php

namespace App\Models;

use App\Enums\Game\GameStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property GameStatusEnum $status
 * @property \Illuminate\Support\Carbon|null $valid_until
 * @property \Illuminate\Support\Carbon|null $finished_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Player> $players
 * @property-read int|null $players_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Round> $rounds
 * @property-read int|null $rounds_count
 *
 * @method static Builder|Game active()
 * @method static \Database\Factories\GameFactory factory($count = null, $state = [])
 * @method static Builder|Game newModelQuery()
 * @method static Builder|Game newQuery()
 * @method static Builder|Game notFinished()
 * @method static Builder|Game query()
 * @method static Builder|Game valid()
 * @method static Builder|Game whereCreatedAt($value)
 * @method static Builder|Game whereFinishedAt($value)
 * @method static Builder|Game whereId($value)
 * @method static Builder|Game whereStatus($value)
 * @method static Builder|Game whereUpdatedAt($value)
 * @method static Builder|Game whereValidUntil($value)
 *
 * @mixin \Eloquent
 */
class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'valid_until',
        'finished_at',
        'status',
    ];

    protected $casts = [
        'valid_until' => 'datetime',
        'finished_at' => 'datetime',
        'status' => GameStatusEnum::class,
    ];

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class);
    }

    public function addPlayer(Player $player): void
    {
        $this->players()->attach($player);
    }

    public function removePlayer(Player $player): void
    {
        $this->players()->detach($player);
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class);
    }

    public function scopeValid(Builder $query): void
    {
        $query->where('valid_until', '>=', Carbon::now());
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', '=', GameStatusEnum::ACTIVE);
    }

    public function getStatusText(): string
    {
        return match ($this->status) {
            GameStatusEnum::ACTIVE => 'active',
            GameStatusEnum::FINISHED => 'finished'
        };
    }

    public function scopeNotFinished(Builder $query): void
    {
        $query->where('finished_at', '=', null);
    }

    public function getActualRound(): ?Round
    {
        return $this->rounds()->latest()->first();
    }
}
