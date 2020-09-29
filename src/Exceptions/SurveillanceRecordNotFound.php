<?php

namespace Neelkanth\Laravel\Surveillance\Exceptions;

use Exception;

class SurveillanceRecordNotFound extends Exception
{
    public function __construct()
    {
        $this->message = 'No record found.';
        $this->code = 1404;
        parent::__construct($this->message, $this->code);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
