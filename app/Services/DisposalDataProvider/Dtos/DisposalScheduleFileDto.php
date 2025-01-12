<?php

namespace App\Services\DisposalDataProvider\Dtos;

class DisposalScheduleFileDto
{
    public function __construct(
        /** The address element that schedule applies to. */
        public IAddressElement $addressElement,
        /** The file path to the schedule. */
        public string $filePath,
    ) {
    }
}