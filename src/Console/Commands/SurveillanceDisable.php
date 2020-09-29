<?php

namespace Neelkanth\Laravel\Surveillance\Console\Commands;

use Illuminate\Console\Command;
use Neelkanth\Laravel\Surveillance\Interfaces\SurveillanceManagerInterface;
use Exception;
use Neelkanth\Laravel\Surveillance\Helpers\ConsoleHelper;

class SurveillanceDisable extends Command
{
    use \Neelkanth\Laravel\Surveillance\Traits\ValidateCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'surveillance:disable {type} {value}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop logging surveillance logs for [type] and [value]';

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
            if (is_null($surveillance->getRecord())) {
                $this->error(trans("surveillance.messages.record-not-found", ["type" => $type, "value" => $value]));
                exit(1);
            } else if (!$surveillance->isSurveillanceEnabled()) {
                $this->info(trans("surveillance.messages.already-disabled"));
            } else {
                $surveillance->disableSurveillance();
                if (!$surveillance->isSurveillanceEnabled()) {
                    $this->info(trans("surveillance.messages.disabled"));
                }
            }
            $output = ConsoleHelper::formatConsoleTableOutput($surveillance->getRecord());
            if (!empty($output["rows"])) {
                $this->table($output["header"], $output["rows"]);
            }
        } catch (Exception $ex) {
            $this->error($ex->getMessage());
        }
    }
}
