<?php

namespace App\Services\DisposalScheduleProvider\DTOs;

class LocationDTO
{
    public string $town;
    public array $streets = [];

    public function __construct(string $town, array $streets)
    {
        $this->town = $town;
        $this->streets = $streets;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['Town'],
            $data['Streets']
        );
    }

    public function toArray(): array
    {
        return [
            'Town' => $this->town,
            'Streets' => $this->streets,
        ];
    }
}

