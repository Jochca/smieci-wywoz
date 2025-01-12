<?php

namespace App\Services\DisposalFileProvider\Dtos;

/**
 * Represents a file that describes the disposal schedule.
 */
class FileDto
{
    public function __construct(
        /** Url to the file. */
        public string $url,
    ) {
    }
}