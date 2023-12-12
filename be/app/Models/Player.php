<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'points'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function addPoints(int $points): self
    {
        $this->points += $points;

        return $this;
    }
}
