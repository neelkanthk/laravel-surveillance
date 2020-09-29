<?php

namespace Neelkanth\Laravel\Surveillance\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait ValidateCommand
{
    /**
     * Validate the CLI input
     *
     * @param [type] $args
     * @return void
     */
    public function validateArguments($args)
    {
        $allowedTypes = config("surveillance.allowed-types");
        $validator = Validator::make(
            ['type' => $args["type"]],
            ['type' => [Rule::in($allowedTypes)]],
            [trans("surveillance.messages.console-allowed-types", ["types" => implode(", ", $allowedTypes)])]
        );
        if ($validator->fails()) {
            $this->info(trans("surveillance.messages.console-validation-failed"));
            foreach ($validator->errors()->all() as $error) {
                $this->error($error . "\n");
            }
            exit(1);
        }
    }
}
