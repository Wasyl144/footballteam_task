<?php

namespace App\Dtos\Game\ActualInfo;

use Illuminate\Support\Collection;

readonly class ActualGameInfoDto
{
    public function __construct(
        public int $round,
        public int $yourPoints,
        public int $opponentPoints,
        public string $status,
        public Collection $cards,
    ) {
    }
}
