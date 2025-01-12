<?php

namespace App\Console\Commands;

use App\GPT\Actions\DataHarvestAction\DataHarvestActionGPTAction;
use App\Models\Disposals;
use App\Models\Town;
use App\Models\TownStreet;
use App\Services\GPT\AgregatorService;
use App\Services\PDF\PdfToTxtService;
use Illuminate\Console\Command;
use OpenAI\Client;

class ImportDataCommand extends Command
{
    public function __construct(
        private AgregatorService $agregatorService,
        private PdfToTxtService $pdfToTxtService
    )
    {
        parent::__construct();
    }


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-data-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = storage_path('app/exmaple.pdf');

        $fileText = $this->pdfToTxtService->convertToText($file);

//        $disposals = $this->agregatorService->harvest($fileText);

        $json = '{
  "Schedule": {
    "Location": {
      "Town": "Jarocin",
      "Streets": ["Akacjowa", "700-lecia", "1000-lecia", "Batorego", "Bema", "Bohaterów Jarocina", "Brandowskiego", "Długa", "Franciszkańska", "Gen. Sikorskiego", "Glinki", "Gorzeńskiego", "Harcerska", "Jaśminowa", "Jarmarczna", "Jordana", "Karwowskiego", "Kasprzaka", "Kirchnera", "Kr. Jadwigi", "Krucza", "Kusocińskiego", "Leszczyce", "Lisia", "Łabędzkiego", "Magnoliowa", "Malinowskiego", "Maratońska", "Matuszaka", "Mikołajczyka", "Niedbały", "Niedźwiedzińskiego", "Okrężna", "Olimpijska", "Os. Konstytucji", "Park", "Parkowa", "Parowozownia", "Piaskowa", "Podchorążych", "Popiełuszki", "Powst. Wlkp", "Rogalskiego", "Sarnia", "Skarzyńskiego", "Skłodowskiej", "Skowronkowa", "Słowackiego", "Solidarności", "Sowia", "Sportowa", "Stawna", "Śniadeckich", "Św. Ducha", "Taczaka", "Traugutta", "Veldhoven", "Walentynowicz", "Waryńskiego", "Wilcza", "Wojska Polskiego", "Zajęcza", "Zamkowa", "Zapłocie", "Żerkowska", "Żurawia", "Żwirki i Wigury"]
    },
    "Period": {
      "Start": "2025-01-01",
      "End": "2025-06-30"
    },
    "types": [
      {
        "Type": "mixed",
        "Dates": ["2025-01-03", "2025-01-21", "2025-02-05", "2025-02-19", "2025-03-05", "2025-03-19", "2025-04-02", "2025-04-16", "2025-05-06", "2025-05-20", "2025-06-03", "2025-06-17"]
      },
      {
        "Type": "bio",
        "Dates": ["2025-01-14", "2025-01-28", "2025-02-11", "2025-02-25", "2025-03-11", "2025-03-25", "2025-04-08", "2025-04-22", "2025-05-06", "2025-05-20", "2025-06-02", "2025-06-16"]
      },
      {
        "Type": "ash",
        "Dates": ["2025-01-14", "2025-01-28", "2025-02-11", "2025-02-25", "2025-03-11", "2025-03-25", "2025-04-08", "2025-04-22", "2025-05-08", "2025-05-23"]
      },
      {
        "Type": "recyclable",
        "Dates": ["2025-01-09", "2025-01-28", "2025-02-07", "2025-02-13", "2025-02-20", "2025-02-27", "2025-03-03", "2025-03-10", "2025-03-17", "2025-03-24", "2025-03-31", "2025-04-07", "2025-04-14", "2025-04-22", "2025-04-28", "2025-05-05", "2025-05-12", "2025-05-19", "2025-05-26", "2025-06-02", "2025-06-05", "2025-06-09", "2025-06-12", "2025-06-16", "2025-06-20", "2025-06-23", "2025-06-26", "2025-06-30"]
      },
      {
        "Type": "green",
        "Dates": ["2025-01-28", "2025-02-06", "2025-02-25", "2025-03-07", "2025-03-14", "2025-03-21", "2025-03-28", "2025-04-07", "2025-04-14", "2025-04-21", "2025-04-28", "2025-05-05", "2025-05-12", "2025-05-19", "2025-05-26", "2025-06-06", "2025-06-10", "2025-06-13", "2025-06-16", "2025-06-20", "2025-06-23", "2025-06-26", "2025-06-30"]
      }
    ],
    "Notes": [
      "Waste must be placed outside before 6:00 AM.",
      "Green waste is collected only in 240L bins labeled.",
      "Bagged segregated waste should be full and tied.",
      "Green waste is collected only in 240L bins labeled . If additional bins are needed, please contact Sp. z o.o. at Witaszyczki ul. im. Mariusza Małynicza 1, 63-200 Jarocin.",
      "Mixed municipal waste, biodegradable waste, ash, and green waste are collected from containers equipped with chips. For chip installation, please contact tel. 534 051 743 or email: sekretariat@zgo-nova.pl. This obligation arises from the regulations for maintaining cleanliness and order in the Jarocin Commune."
    ]
  }
}';
        $disposals = json_decode($json, true);

//            dd($disposals);

            $town = Town::where('name', $disposals['Schedule']['Location']['Town'])->first();

            if(!$town) {
                $town = Town::create([
                    'name' => $disposals['Schedule']['Location']['Town']
                ]);
            }

            if(count($disposals['Schedule']['Location']['Streets']) > 0) {
                foreach ($disposals['Schedule']['Location']['Streets'] as $street) {

                    $streetObject = TownStreet::where('name', $street)->first();

                    if(!$streetObject) {
                        $streetObject = TownStreet::create([
                            'name' => $street,
                            'town_id' => $town->id
                        ]);
                    }

                    foreach($disposals['Schedule']['types'] as $wasteType) {
                        foreach($wasteType['Dates'] as $date) {

                            $disposalObject = Disposals::where('town_id', $town->id)
                                ->where('town_street_id', $streetObject->id)
                                ->where('date', $date)
                                ->where('type', $wasteType['Type'])
                                ->first();

                            if(!$disposalObject) {
                                Disposals::create([
                                    'town_id' => $town->id,
                                    'town_street_id' => $streetObject->id,
                                    'date' => $date,
                                    'type' => $wasteType['Type'],
                                ]);
                            }
                        }
                    }
                }
            } else {
                foreach($disposals['Schedule']['types'] as $wasteType) {
                    foreach($wasteType['Dates'] as $date) {

                        $disposalObject = Disposals::where('town_id', $town->id)
                            ->where('date', $date)
                            ->where('type', $wasteType['Type'])
                            ->first();

                        if(!$disposalObject) {
                            Disposals::create([
                                'town_id' => $town->id,
                                'date' => $date,
                                'type' => $wasteType['Type'],
                            ]);
                        }
                    }
                }
            }

    }
}
