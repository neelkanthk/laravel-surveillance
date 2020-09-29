<?php

namespace Neelkanth\Laravel\Surveillance\Console\Commands;

use Illuminate\Console\Command;
use Neelkanth\Laravel\Surveillance\Interfaces\SurveillanceManagerInterface;
use Exception;
use Neelkanth\Laravel\Surveillance\Helpers\ConsoleHelper;

class SurveillanceRemoveRecord extends Command
{
    use \Neelkanth\Laravel\Surveillance\Traits\ValidateCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'surveillance:remove {type} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove a surveillance manager record.';

    protected $surveillance;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SurveillanceManagerInterface $binding)
    {
        parent::__construct();
        $this->surveillance = $binding;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->validateArguments($this->arguments());
            $type = $this->argument("type");
            $value = $this->argument("value");
            $surveillance = $this->surveillance->setType($type)->setValue($value);

            $records = $surveillance->getRecords();
            if ($records->count() > 0) {
                $output = ConsoleHelper::formatConsoleTableOutput($surveillance->getRecords());
                if (!empty($output["rows"])) {
                    $this->table($output["header"], $output["rows"]);
                }

                $confirmation = $this->confirm(trans("surveillance.messages.confirm"));

                if ($confirmation) {
                    $removeStatus = $surveillance->removeRecord();
                }
                if ($removeStatus) {
                    $this->info(trans("surveillance.messages.record-removed"));
                }
            } else {
                $this->error(trans("surveillance.messages.record-not-found", ["type" => $type, "value" => $value]));
                exit(1);
            }
        } catch (Exception $ex) {
            $this->error($ex->getMessage());
        }
    }
}
