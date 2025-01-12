<?php

namespace App\Services\DisposalDataProvider\Dtos;

use Illuminate\Support\Collection;

class TownDto implements IAddressElement
{
    public function __construct(
        /** Name of the town. */
        public string $name,
        /** @var Collection<TownStreetDto> Streets belonging to town. */
        public Collection $streets,
    ) {
    }
}