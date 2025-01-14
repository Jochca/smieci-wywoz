<?php

namespace App\Services\DisposalScheduleProvider\Interfaces;

use App\Services\DisposalScheduleProvider\DTOs\DisposalScheduleDTO;

interface IDisposalScheduleProvider
{
    public function extract(string $data): DisposalScheduleDTO;
}
