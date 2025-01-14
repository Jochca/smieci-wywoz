<?php

namespace App\Services\DisposalScheduleProvider\DTOs;

class PeriodDTO
{
    public string $start;
    public string $end;

    public function __construct(string $start, string $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['Start'],
            $data['End']
        );
    }

    public function toArray(): array
    {
        return [
            'Start' => $this->start,
            'End' => $this->end,
        ];
    }
}
