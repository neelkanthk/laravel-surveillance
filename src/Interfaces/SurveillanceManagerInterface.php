<?php

namespace Neelkanth\Laravel\Surveillance\Interfaces;

interface SurveillanceManagerInterface
{
    /**
     * Get the surveillance type
     */
    public function getType();

    /**
     * Get the surveillance value
     */
    public function getValue();

    /**
     * Set the surveillance type
     *
     * @param [string] $type
     */
    public function setType($type);

    /**
     * Set the surveillace value
     *
     * @param [int/string] $value
     */
    public function setValue($value);

    /**
     * Enable surveillance logging for a type and value
     */
    public function enableSurveillance();

    /**
     * Disable surveillance logging for a type and value
     */
    public function disableSurveillance();

    /**
     * Block access for a type and value
     */
    public function blockAccess();

    /**
     * Unblock access for a type and value
     */
    public function unblockAccess();

    /**
     * Delete the surveillance manager record from database
     */
    public function removeRecord();

    /**
     * Get a single surveillance manager record from database for a type and value
     */
    public function getRecord();

    /**
     * Get a multiple surveillance manager record from database for a type and value
     */
    public function getRecords();

    /**
     * Checks if access is blocked or not
     */
    public function isAccessBlocked();

    /**
     * Checks if surveillance is enabled ot not
     */
    public function isSurveillanceEnabled();

    /**
     * Get a single surveillance record by its id from database
     *
     * @param [int] $id
     * @return void
     */
    public function getRecordById(int $id);

    /**
     * Return a paginated and filtered list of the surveillance records
     *
     * @param [array] $filters
     * @return array
     */
    public function getPaginatedAndFilteredRecords($filters = array());

    /**
     * Delete surveillance record by its id from database
     *
     * @param [int] $id
     * @return void
     */
    public function removeRecordById(int $id);

    /**
     * Get count of total surveillance records from database
     * @return int
     */
    public function totalRecords();
}
