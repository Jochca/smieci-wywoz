<?php

namespace App\Services\DisposalDataProvider\Dtos;

use Illuminate\Support\Collection;

class TownStreetDto implements IAddressElement
{
    public function __construct(
        /** Name of the street. */
        public string $name,
    ) {
    }
}