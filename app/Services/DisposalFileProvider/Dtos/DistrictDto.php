<?php

namespace App\Services\DisposalFileProvider\Dtos;

use Illuminate\Support\Collection;

class DistrictDto
{
    public function __construct(
        /** Name of the district. */
        public string $name,

        /** @var Collection<FileDto> Files that describe schedules in district. */
        public Collection $towns,
    ) {
    }
}