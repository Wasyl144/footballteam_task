<?php

namespace App\Models;

use App\Enums\Game\GameStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
