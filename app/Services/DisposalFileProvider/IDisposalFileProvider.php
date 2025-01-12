<?php

namespace App\Services\DisposalFileProvider;

use App\Services\DisposalFileProvider\Dtos\DistrictDto;
use Illuminate\Support\Collection;

/**
 * Provides files about the disposal schedule.
 */
interface IDisposalFileProvider
{
    /**
     * Retrieves the available districts from the source.
     * 
     * @return \Illuminate\Support\Collection<DistrictDto>
     */
    public function getDistrictsWithFiles(): Collection;
}
