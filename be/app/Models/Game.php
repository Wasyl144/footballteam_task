<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'valid_until',
        'finished_at',
        'status',
    ];

    //TODO: cast to carbon and cast to status enum
    protected $casts = [

    ];

    public function players()
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
}