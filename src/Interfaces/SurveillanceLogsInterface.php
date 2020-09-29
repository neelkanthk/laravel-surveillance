<?php

namespace Neelkanth\Laravel\Surveillance\Interfaces;

use Illuminate\Http\Request;

interface SurveillanceLogInterface
{
    /**
     * Retrieve the data from Request for logging
     *
     * @param Request $request
     */
    public function makeLogFromRequest(Request $request);

    /**
     * Write the log in the database
     *
     * @param [array] $dataToLog
     */
    public function writeLog($dataToLog = null);
}
