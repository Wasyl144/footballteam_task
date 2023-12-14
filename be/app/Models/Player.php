<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'points', 'level'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deck(): HasOne
    {
        return $this->hasOne(Deck::class);
    }

    public function isAllowToGetNewCard(): bool
    {
        // TODO: to implement

        return false;
    }
}
