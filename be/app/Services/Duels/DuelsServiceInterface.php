<?php

namespace App\Services\Duels;

use App\Dtos\Duels\DuelsHistoryDto;

interface DuelsServiceInterface
{
    /**
     * @return DuelsHistoryDto[]
     */
    public function getDuelsHistoryByUser(int $userId): array;
}
