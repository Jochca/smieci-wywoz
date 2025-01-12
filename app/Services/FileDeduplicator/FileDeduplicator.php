<?php

namespace App\Services\FileDeduplicator;

use App\Models\ProcessedFile;
use Illuminate\Support\Collection;

/**
 * Service which aids resource saving by keeping track of files that were already processed
 * and avoiding processing them again.
 */
class FileDeduplicator
{
    /** Marks file as processed. */
    public function markFileAsProcessed(string $file): void
    {
        if (!file_exists($file)) {
            throw new \InvalidArgumentException("File \"$file\" does not exist.");
        }

        $hash = hash_file("sha1", $file);

        $alreadyProcessed = ProcessedFile::where("hash", $hash)->exists();

        if ($alreadyProcessed) {
            throw new \RuntimeException("File \"$file\" was already processed.");
        }

        ProcessedFile::create(["hash" => $hash]);
    }

    /** 
     * Deduplicate files. 
     * 
     * Returns only files that were not processed yet.
     *
     * @param Collection<string> $files Collection of file paths.
     */
    public function deduplicateFiles(Collection $files): Collection
    {
        $fileWithHashes = collect([]);

        foreach ($files as $file) {
            if (!file_exists($file)) {
                throw new \InvalidArgumentException("File \"$file\" does not exist.");
            }

            $hash = hash_file("sha1", $file);

            $fileWithHashes[$hash] = $file;
        }

        $alreadyProcessed = ProcessedFile::whereIn("hash", $fileWithHashes->keys())->pluck("hash");

        return $fileWithHashes->except($alreadyProcessed->all());
    }
}