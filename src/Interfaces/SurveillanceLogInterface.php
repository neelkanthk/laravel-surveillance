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

    /**
     * Return a paginated and filtered list of the logs
     *
     * @param [array] $filters
     * @return array
     */
    public function getPaginatedAndFilteredLogs($filters = array());

    /**
     * Get a single log by its id from database
     *
     * @param [int] $id
     * @return void
     */
    public function getLogById(int $id);

    /**
     * Delete log by its id from database
     *
     * @param [int] $id
     * @return void
     */
    public function deleteLogById(int $id);

    /**
     * Get count of total logs from database
     * @return int
     */
    public function totalLogs();
}
