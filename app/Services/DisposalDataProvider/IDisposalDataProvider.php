<?php

namespace App\Services\DisposalFileProvider;

use App\Services\DisposalDataProvider\Dtos\DistrictDto;
use App\Services\DisposalDataProvider\Dtos\DisposalScheduleFileDto;
use Illuminate\Support\Collection;


interface IDisposalDataProvider
{
    /**
     * Downloads the available districts from the source.
     * 
     * @return \Illuminate\Support\Collection<DistrictDto>
     */
    public function getAvailableDistricts(): Collection;

    /**
     * Downloads the disposal schedule files from the source.
     * 
     * @return \Illuminate\Support\Collection<DisposalScheduleFileDto>
     */
    public function getDisposalScheduleFiles(): Collection;
}
