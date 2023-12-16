<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Level
 *
 * @property int $id
 * @property int $level_number
 * @property int $points_from
 * @property int $points_to
 * @property int $max_cards
 *
 * @method static \Database\Factories\LevelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Level newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Level newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Level query()
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereLevelNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level whereMaxCards($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level wherePointsFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Level wherePointsTo($value)
 *
 * @mixin \Eloquent
 */
class Level extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'number',
        'points_from',
        'points_to',
        'max_cards',
    ];
}
