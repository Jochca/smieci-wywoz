<?php

namespace App\Console\Commands;

use App\Models\Disposals;
use App\Models\Town;
use App\Models\TownStreet;
use App\Services\DisposalImportHandler\DisposalImportHandler;
use App\Services\GPT\GPTScheduleProvider;
use Illuminate\Console\Command;
use OpenAI\Client;

class ImportDataCommand extends Command
{
    public function __construct(
        private DisposalImportHandler $disposalImportHandler,
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
        $this->disposalImportHandler->handle();
    }
}
