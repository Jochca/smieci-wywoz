<?php

namespace App\Services\DisposalFileProvider\Concrete;

use App\Services\DisposalFileProvider\Dtos\DistrictDto;
use App\Services\DisposalFileProvider\Dtos\FileDto;
use App\Services\DisposalFileProvider\IDisposalFileProvider;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class WebScrappingDisposalFileProvider implements IDisposalFileProvider
{
    public function __construct(
        protected Client $client,
        protected Crawler $crawler,
    ) {
    }

    /** The url which contains the data. */
    public const string SCHEDULE_URL = "https://zgo-nova.pl/harmonogramy-odbioru-odpadow/";

    /**
     * Retrieves the available districts from the source.
     * 
     * @return \Illuminate\Support\Collection<DistrictDto>
     */
    public function getFiles(): Collection
    {
        $districts = $this->scrapeAvailableDistricts();

        $districtsWithFiles = $districts->map(function ($district) {
            $files = $this->scrapeFilesForDistrict($district["href"]);

            return new DistrictDto(
                $district["name"],
                $files
            );
        });

        return $districtsWithFiles;
    }

    /**
     * Scrapes the available districts from the source.
     * 
     * @return Collection<{name: string, href: string}>
     */
    protected function scrapeAvailableDistricts(): Collection
    {
        $request = $this->client->request("GET", self::SCHEDULE_URL);
        $html = $request->getBody()->getContents();

        $this->crawler->clear();
        $this->crawler->addHtmlContent($html);

        $districtsListHolder = $this->crawler
            ->filter("ul#menu-boczne-harmonogramy")
            ->first();

        $districtsList = $districtsListHolder
            ->filter("li")
            ->each(function (Crawler $node) {
                $districtName = $node->filter("a")->text();
                $districtHref = $node->filter("a")->attr("href");

                return [
                    "name" => $districtName,
                    "href" => $districtHref,
                ];
            });

        if (count($districtsList) === 0) {
            throw new \Exception("No districts found in the content.");
        }

        return collect($districtsList);
    }

    /**
     * Scrapes the available files for the district.
     * 
     * @param string $districtHref The href to the district.
     * @return Collection<FileDto>
     */
    protected function scrapeFilesForDistrict(string $districtHref): Collection
    {
        $request = $this->client->request("GET", $districtHref);
        $html = $request->getBody()->getContents();

        $this->crawler->clear();
        $this->crawler->addHtmlContent($html);

        $content = $this->crawler->filter(".content.entry")->first();
        $tables = $content->filter("table");

        if ($tables->count() === 0) {
            throw new \Exception("No tables found in the content.");
        }

        $tableFiles = $tables->each(fn($table) => $this->extractFilesFromTable($table));

        return collect($tableFiles)
            ->flatten()
            ->map(fn($file) => new FileDto($file));
    }

    /**
     * Extracts the files from the table.
     * 
     * @return Collection<FileDto>
     */
    protected function extractFilesFromTable(Crawler $table): Collection
    {
        $linksList = $table
            ->filter("tr a")
            ->each(fn($node) => $node->attr("href"));

        return collect($linksList);
    }

}