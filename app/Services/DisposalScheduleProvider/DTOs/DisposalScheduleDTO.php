<?php

namespace App\Services\DisposalScheduleProvider\DTOs;

class DisposalScheduleDTO
{
    public LocationDTO $location;
    public PeriodDTO $period;
    public array $wasteTypes = []; // Array of WasteTypeDTO
    public array $notes = [];

    public function __construct(
        LocationDTO $location,
        PeriodDTO $period,
        array $wasteTypes,
        array $notes
    ) {
        $this->location = $location;
        $this->period = $period;
        $this->wasteTypes = $wasteTypes;
        $this->notes = $notes;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            LocationDTO::fromArray($data['Schedule']['Location']),
            PeriodDTO::fromArray($data['Schedule']['Period']),
            array_map(fn($type) => WasteTypeDTO::fromArray($type), $data['Schedule']['Waste_Types']),
            $data['Schedule']['Notes']
        );
    }

    public function toArray(): array
    {
        return [
            'Location' => $this->location->toArray(),
            'Period' => $this->period->toArray(),
            'Waste_Types' => array_map(fn(WasteTypeDTO $type) => $type->toArray(), $this->wasteTypes),
            'Notes' => $this->notes,
        ];
    }
}
