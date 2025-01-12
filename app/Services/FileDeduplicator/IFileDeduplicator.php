<?php

namespace App\Services\FileDeduplicator;

use App\Models\ProcessedFile;
use Illuminate\Support\Collection;

/**
 * Service which aids resource saving by keeping track of files that were already processed
 * and avoiding processing them again.
 */
interface IFileDeduplicator
{
    /** Marks file as processed. */
    public function markFileAsProcessed(string $file): void;

    /** 
     * Deduplicate files. 
     * 
     * Returns only files that were not processed yet.
     *
     * @param Collection<string> $files Collection of file paths.
     */
    public function deduplicateFiles(Collection $files): Collection;
}