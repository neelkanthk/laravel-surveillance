<?php

namespace Neelkanth\Laravel\Surveillance\Services;

class Surveillance
{
    protected $surveillanceManager;
    protected $surveillanceLogger;

    protected $type;
    protected $value;

    public static function manager()
    {
        return (new static())->surveillanceManager();
    }

    public static function logger()
    {
        return (new static())->surveillanceLogger();
    }

    /**
     * Set the surveillance type
     *
     * @param [string] $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Set the surveillance value
     *
     * @param [string] $value
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Resolve SurveillanceManagerInterface instance
     *
     * @return $this
     */
    public function surveillanceManager()
    {
        $this->surveillanceManager = app()->make('Neelkanth\Laravel\Surveillance\Interfaces\SurveillanceManagerInterface');
        return $this;
    }

    /**
     * Resolve SurveillanceLogInterface instance
     *
     * @return $this
     */
    public function surveillanceLogger()
    {
        $this->surveillanceLogger = app()->make('Neelkanth\Laravel\Surveillance\Interfaces\SurveillanceLogInterface');
        return $this;
    }

    /**
     * Enable surveillance logging for a type and value
     *
     * @return SurveillanceManager
     */
    public function enableSurveillance()
    {
        return $this->surveillanceManager->setType($this->type)->setValue($this->value)->enableSurveillance();
    }

    /**
     * Disable surveillance logging for a type and value
     *
     * @return SurveillanceManager
     */
    public function disableSurveillance()
    {
        return $this->surveillanceManager->setType($this->type)->setValue($this->value)->disableSurveillance();
    }

    /**
     * Block access for a type and value
     *
     * @return SurveillanceManager
     */
    public function blockAccess()
    {
        return $this->surveillanceManager->setType($this->type)->setValue($this->value)->blockAccess();
    }

    /**
     * Unblock access for a type and value
     *
     * @return SurveillanceManager
     */
    public function unblockAccess()
    {
        return $this->surveillanceManager->setType($this->type)->setValue($this->value)->unblockAccess();
    }

    /**
     * Delete the surveillance manager record from database
     *
     * @return int
     */
    public function removeRecord()
    {
        return $this->surveillanceManager->setType($this->type)->setValue($this->value)->removeRecord();
    }

    /**
     * Write Log
     *
     * @return void
     */
    public function writeLog()
    {
        return $this->surveillanceLogger->makeLogFromRequest(request())->writeLog();
    }

    /**
     * Check if surveillance is enabled
     *
     * @param [string] $userId
     * @param [string] $ipAddress
     * @param [string] $fingerprint
     * @return boolean
     */
    public function isSurveillanceEnabled($userId = null, $ipAddress = null, $fingerprint = null)
    {
        return $this->surveillanceManager->isSurveillanceEnabled($userId, $ipAddress, $fingerprint);
    }

    /**
     * Check if access is blocked
     *
     * @param [string] $userId
     * @param [string] $ipAddress
     * @param [string] $fingerprint
     * @return boolean
     */
    public function isAccessBlocked($userId = null, $ipAddress = null, $fingerprint = null)
    {
        return $this->surveillanceManager->isAccessBlocked($userId, $ipAddress, $fingerprint);
    }

    /**
     * Get a single surveillance record by its id from database
     *
     * @param [int] $id
     * @return void
     */
    public function getRecordById(int $id)
    {
        return $this->surveillanceManager->getRecordById($id);
    }

    /**
     * Return a paginated and filtered list of the surveillance records
     *
     * @param [array] $filters
     * @return array
     */
    public function getPaginatedAndFilteredRecords($filters = array())
    {
        return $this->surveillanceManager->getPaginatedAndFilteredRecords($filters);
    }

    /**
     * Delete surveillance record by its id from database
     *
     * @param [int] $id
     * @return bool
     */
    public function removeRecordById(int $id)
    {
        return $this->surveillanceManager->removeRecordById($id);
    }

    /**
     * Get count of total surveillance records from database
     * @return int
     */
    public function totalRecords()
    {
        return $this->surveillanceManager->totalRecords();
    }

    /**
     * Get a single surveillance log by its id from database
     *
     * @param [int] $id
     * @return void
     */
    public function getLogById(int $id)
    {
        return $this->surveillanceLogger->getLogById($id);
    }

    /**
     * Return a paginated and filtered list of the surveillance logs
     *
     * @param [array] $filters
     * @return array
     */
    public function getPaginatedAndFilteredLogs($filters = array())
    {
        return $this->surveillanceLogger->getPaginatedAndFilteredLogs($filters);
    }

    /**
     * Delete surveillance log by its id from database
     *
     * @param [int] $id
     * @return bool
     */
    public function deleteLogById(int $id)
    {
        return $this->surveillanceLogger->deleteLogById($id);
    }

    /**
     * Get count of total surveillance logs from database
     * @return int
     */
    public function totalLogs()
    {
        return $this->surveillanceLogger->totalLogs();
    }
}
