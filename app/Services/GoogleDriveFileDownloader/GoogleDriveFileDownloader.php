<?php

namespace App\Services\GoogleDriveFileDownloader;

use GuzzleHttp\Client;
use Spatie\TemporaryDirectory\TemporaryDirectory;

/**
 * Service for downloading files from Google Drive.
 */
class GoogleDriveFileDownloader
{
    public function __construct(
        protected Client $client,
        protected TemporaryDirectory $temporaryDirectory
    ) {
        $this->temporaryDirectory->create();
    }

    /** Downloads a file from Google Drive and returns a path to it. */
    public function downloadFile(string $fileUrl): string
    {
        $fileId = $this->getFileId($fileUrl);
        $downloadUrl = $this->getDownloadUrl($fileId);
        $filePath = $this->temporaryDirectory->path($fileId . ".tmp");

        $response = $this->client->get($downloadUrl);

        file_put_contents($filePath, $response->getBody());

        return $filePath;
    }

    /** Extracts file ID from a Google Drive URL. */
    public function getFileId(string $fileUrl): string
    {
        $fileIdRegex = '/\/file\/d\/([^\/]+)/';
        $matches = [];

        preg_match($fileIdRegex, $fileUrl, $matches);

        if (count($matches) >= 2) {
            return $matches[1];
        }

        throw new \Exception("Invalid Google Drive URL.");
    }

    /** Returns download url from file ID. */
    public function getDownloadUrl(string $fileId): string
    {
        return "https://drive.google.com/uc?export=download&id=$fileId";
    }
}