<?php

namespace App\Services\DisposalScheduleProvider\Concrete;

use App\Services\DisposalScheduleProvider\DTOs\DisposalScheduleDTO;
use App\Services\DisposalScheduleProvider\Interfaces\IDisposalScheduleProvider;
use OpenAI\Client;
use OpenAI\Transporters\HttpTransporter;

class GPTScheduleProvider implements IDisposalScheduleProvider
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function extract(string $data) : DisposalScheduleDTO
    {
        $messages = [
            [
                'role' => 'system',
                'content' => "You are an assistant that extracts waste collection schedules from text and converts them into a JSON format. You have to return JSON and JSON only in basic text format.",
            ],
            [
                'role' => 'user',
                'content' => 'You are an assistant that extracts waste collection schedules from textual data. The data contains a table where rows represent months (e.g., January, February) and columns represent different types of waste. Each cell contains days of the month when the waste is collected. Your task is to match these days to their corresponding months and generate a JSON structure like the one below:

                    {
                      "Schedule": {
                        "Location": {
                          "Town": "Jarocin",
                          "Streets": ["Akacjowa", "700-lecia", "1000-lecia", "..."]
                        },
                        "Period": {
                          "Start": "2025-01-01",
                          "End": "2025-06-30"
                        },
                        "Waste_Types": [
                          {
                            "Type": "mixed",
                            "Dates": ["2025-01-03", "2025-01-21", "..."]
                          },
                          {
                            "Type": "bio",
                            "Dates": ["2025-01-14", "2025-01-28", "..."]
                          },
                          {
                            "Type": "ash",
                            "Dates": ["2025-01-14", "2025-01-28", "..."]
                          },
                          {
                            "Type": "recyclable",
                            "Dates": ["2025-01-09", "..."]
                          },
                          {
                            "Type": "green",
                            "Dates": ["2025-01-28", "..."]
                          }
                        ],
                        "Notes": [
                          "Waste must be placed outside before 6:00 AM.",
                          "Green waste is collected only in 240L bins labeled ."
                        ]
                      }
                    }

                    Instructions for interpreting the table:
                    1. Each row begins with a month (e.g., January, February, etc.).
                    2. The columns represent:
                       - Mixed Municipal Waste,
                       - Biodegradable Kitchen Waste,
                       - Ash,
                       - Glass, Paper, Plastics, Metals,
                       - Green Waste.
                    3. Dates in each cell correspond to collection days within the given month.
                    4. Combine the month with the days to form complete dates in YYYY-MM-DD format. Check if date is correct (e.g., February 30th).
                    5. If there is data about other types of waste, omit them.
                    '.$data
            ],
        ];

        $response = $this->client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'max_tokens' => 1500,
            'temperature' => 0.7,
        ]);

        $content =  $response['choices'][0]['message']['content'];

        if(!json_decode($content)) {
            throw new \Exception('Invalid JSON format');
        }

        return DisposalScheduleDTO::fromArray(json_decode($content, true));
    }
}
