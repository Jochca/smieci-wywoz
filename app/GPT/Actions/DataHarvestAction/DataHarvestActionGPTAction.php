<?php

namespace App\GPT\Actions\DataHarvestAction;

use MalteKuhr\LaravelGPT\GPTAction;
use Closure;

class DataHarvestActionGPTAction extends GPTAction
{
    /**
     * The system message explaining the assistant's task and rules to follow.
     *
     * @return string|null
     */
    public function systemMessage(): ?string
    {
        return 'Provided text is pdf parsed to text. The pdf contains a table with waste collection schedule. The task is to convert the text into a JSON format for waste collection schedules. The JSON format should include the town name, streets, period, waste types, dates. The JSON format should be structured as follows. Ensure that JSON is valid.:
        {
  "Schedule": {
    "Location": {
      "Town": "<Town name>",
      "Streets": ["<Street name 1>", "<Street name 2>", "..."] // Optional, can be empty or absent
    },
    "Period": {
      "Start": "<Start date>",
      "End": "<End date>"
    },
    "Waste_Types": [
      {
        "Type": "mixed",
        "Dates": ["<Date1>", "<Date2>", "..."]
      },
      {
        "Type": "bio",
        "Dates": ["<Date1>", "<Date2>", "..."]
      },
      {
        "Type": "ash",
        "Dates": ["<Date1>", "<Date2>", "..."]
      },
      {
        "Type": "recyclable",
        "Dates": ["<Date1>", "<Date2>", "..."]
      },
    {
        "Type": "green",
        "Dates": ["<Date1>", "<Date2>", "..."]
      },
    ],
    "Notes": [
      "<Additional information, e.g., the time for setting out waste>"
    ]
  }
}
        RESPONSE HAVE TO BE JSON AND JSON ONLY';
    }

    /**
     * The function to process the text and convert it to the required JSON format.
     *
     * @return Closure
     */
    public function function(): Closure
    {
        return function (string $text): string {
            return $text;
        };
    }

    /**
     * Validation rules for the input data.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'text' => 'required|string',
        ];
    }

    /**
     * Parses the provided text into the required JSON format for waste collection schedules.
     *
     * @param string $text
     * @return array
     */
    private function parseWasteSchedule(string $text): array
    {
        // Example parsing logic (replace with actual implementation):
        return [
            'Harmonogram' => [
                'Lokalizacja' => [
                    'Miejscowość' => "Example Town",
                    'Ulice' => ["Street 1", "Street 2"]
                ],
                'Okres' => [
                    'Początek' => "2025-01-01",
                    'Koniec' => "2025-12-31"
                ],
                'Typy_odpadów' => [
                    [
                        'Typ' => 'Odpady komunalne zmieszane',
                        'Daty' => ["2025-01-13", "2025-01-27"]
                    ],
                    [
                        'Typ' => 'Bioodpady',
                        'Daty' => ["2025-02-14", "2025-02-28"]
                    ]
                ],
                'Uwagi' => [
                    "Odpady należy wystawić przed posesję do godziny 6:00 rano.",
                    "Worki z odpadami segregowanymi powinny być pełne i zawiązane."
                ]
            ]
        ];
    }
}
