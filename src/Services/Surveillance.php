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
}
