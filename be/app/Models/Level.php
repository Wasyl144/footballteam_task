<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;


    public $timestamps = false;
    protected $fillable = [
        'number',
        'points_from',
        'points_to',
        'max_cards'
    ];
}
