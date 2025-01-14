<?php

namespace App\Services\DisposalScheduleProvider\DTOs;

class WasteTypeDTO
{
    public string $type;
    public array $dates = [];

    public function __construct(string $type, array $dates)
    {
        $this->type = $type;
        $this->dates = $dates;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['Type'],
            $data['Dates']
        );
    }

    public function toArray(): array
    {
        return [
            'Type' => $this->type,
            'Dates' => $this->dates,
        ];
    }
}
