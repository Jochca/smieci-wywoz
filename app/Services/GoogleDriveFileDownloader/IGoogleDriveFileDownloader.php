<?php

namespace App\Services\GoogleDriveFileDownloader;

use GuzzleHttp\Client;
use Spatie\TemporaryDirectory\TemporaryDirectory;

/**
 * Service for downloading files from Google Drive.
 */
interface IGoogleDriveFileDownloader
{
    /** Downloads a file from Google Drive and returns a local path to it. */
    public function downloadFile(string $fileUrl): string;
}