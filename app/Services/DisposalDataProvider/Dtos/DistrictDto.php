<?php

namespace App\Services\DisposalDataProvider\Dtos;

use Illuminate\Support\Collection;

class DistrictDto implements IAddressElement
{
    public function __construct(
        /** Name of the district. */
        public string $name,
        /** @var Collection<TownDto> Towns belonging to district. */
        public Collection $towns,
    ) {
    }
}