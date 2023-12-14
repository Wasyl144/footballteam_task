<?php

namespace App\Dtos\User\Data;

use Illuminate\Support\Collection;

readonly class UserDataDto
{
    public function __construct(
        public int $id,
        public string $name,
        public int $level,
        public string $levelPoints,
        public Collection $cards,
        public bool $isNewCardAllowed
    )
    {
    }
}
