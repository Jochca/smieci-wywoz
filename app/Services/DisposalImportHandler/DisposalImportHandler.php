<?php

namespace App\Services\DisposalImportHandler;

use App\Models\Disposals;
use App\Models\District;
use App\Models\Town;
use App\Models\TownStreet;
use App\Services\DisposalFileProvider\IDisposalFileProvider;
use App\Services\DisposalScheduleProvider\Interfaces\IDisposalScheduleProvider;
use App\Services\GoogleDriveFileDownloader\IGoogleDriveFileDownloader;
use App\Services\PdfToTextTransformer\Interfaces\IPdfToTextConverter;

class DisposalImportHandler
{

    public function __construct(
        private IDisposalFileProvider $disposalFileProvider,
        private IGoogleDriveFileDownloader $googleDriveFileDownloader,
        private IPdfToTextConverter $pdfToTextTransformer,
        private IDisposalScheduleProvider $disposalScheduleProvider
    )
    {
    }

    public function handle()
    {
        $districtDTOs = $this->disposalFileProvider->getDistrictsWithFiles();

        foreach ($districtDTOs as $districtDTO) {
            $district = District::firstOrCreate(['name' => $districtDTO->name]);

            foreach ($districtDTO->files as $file) {
                $file = $this->googleDriveFileDownloader->downloadFile($file->url);
                if (!$file) continue;

                $fileText = $this->pdfToTextTransformer->convert($file);
                $disposals = $this->disposalScheduleProvider->extract($fileText);

                $town = Town::firstOrCreate([
                    'name' => $disposals->location->town,
                    'district_id' => $district->id
                ]);

                foreach ($disposals->location->streets as $street) {
                    $streetObject = TownStreet::firstOrCreate([
                        'name' => $street,
                        'town_id' => $town->id
                    ]);

                    $this->createDisposals($disposals->wasteTypes, $town->id, $streetObject->id);
                }

                if (empty($disposals->location->streets)) {
                    $this->createDisposals($disposals->wasteTypes, $town->id);
                }
            }
        }
    }

    private function createDisposals(array $wasteTypes, int $townId, int $streetId = null)
    {
        foreach ($wasteTypes as $wasteType) {
            foreach ($wasteType->dates as $date) {
                Disposals::firstOrCreate([
                    'town_id' => $townId,
                    'town_street_id' => $streetId,
                    'date' => $date,
                    'type' => $wasteType->type,
                ]);
            }
        }
    }
}
