<?php

namespace Neelkanth\Laravel\Surveillance\Implementations;

use Illuminate\Database\Eloquent\Collection;
use Neelkanth\Laravel\Surveillance\Interfaces\SurveillanceManagerInterface;
use Neelkanth\Laravel\Surveillance\Models\SurveillanceManager;
use Illuminate\Support\Carbon;
use Neelkanth\Laravel\Surveillance\Exceptions\SurveillanceRecordNotFound;

class SurveillanceManagerRepository implements SurveillanceManagerInterface
{
    private $type;
    private $value;

    /**
     * Set the surveillance type
     *
     * @param [string] $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Set the surveillace value
     *
     * @param [int/string] $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get the surveillance type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the surveillance value
     *
     * @return string/int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Enable surveillance logging for a type and value
     *
     * @return SurveillanceManager
     */
    public function enableSurveillance()
    {
        $surveillance = $this->getRecord();
        if (is_null($surveillance)) {
            $surveillance = new SurveillanceManager();
            $surveillance->type = $this->getType();
            $surveillance->value = $this->getValue();
        }
        $surveillance->surveillance_enabled = 1;
        $surveillance->surveillance_enabled_at = Carbon::now()->toDateTimeString();
        $surveillance->save();
        return $surveillance;
    }

    /**
     * Disable surveillance logging for a type and value
     *
     * @return SurveillanceManager
     */
    public function disableSurveillance()
    {
        $surveillance = $this->getRecord();
        if (is_null($surveillance)) {
            throw new SurveillanceRecordNotFound();
        } else {
            $surveillance->surveillance_enabled = 0;
            $surveillance->surveillance_disabled_at = Carbon::now()->toDateTimeString();
            $surveillance->save();
            return $surveillance;
        }
    }

    /**
     * Block access for a type and value
     *
     * @return SurveillanceManager
     */
    public function blockAccess()
    {
        $surveillance = $this->getRecord();
        if (is_null($surveillance)) {
            $surveillance = new SurveillanceManager();
            $surveillance->type = $this->getType();
            $surveillance->value = $this->getValue();
        }
        $surveillance->access_blocked = 1;
        $surveillance->access_blocked_at = Carbon::now()->toDateTimeString();
        $surveillance->save();
        return $surveillance;
    }

    /**
     * Unblock access for a type and value
     *
     * @return SurveillanceManager
     */
    public function unblockAccess()
    {
        $surveillance = $this->getRecord();
        if (is_null($surveillance)) {
            throw new SurveillanceRecordNotFound();
        } else {
            $surveillance->access_blocked = 0;
            $surveillance->access_unblocked_at = Carbon::now()->toDateTimeString();
            $surveillance->save();
            return $surveillance;
        }
    }

    /**
     * Delete the surveillance manager record from database
     *
     * @return int
     */
    public function removeRecord()
    {
        return SurveillanceManager::where("type", $this->getType())
            ->where("value", $this->getValue())
            ->delete();
    }

    /**
     * Get a single surveillance manager record from database for a type and value
     *
     * @return SurveillanceManager
     */
    public function getRecord()
    {
        return SurveillanceManager::where("type", $this->getType())
            ->where("value", $this->getValue())->first();
    }

    /**
     * Get a multiple surveillance manager record from database for a type and value
     *
     * @return Collection
     */
    public function getRecords()
    {
        return SurveillanceManager::where("type", $this->getType())
            ->where("value", $this->getValue())->get();
    }

    /**
     * Checks if access is blocked or not
     *
     * @param [int] $userId
     * @param [string] $ipAddress
     * @param [string] $fingerprint
     * @return boolean
     */
    public function isAccessBlocked($userId = null, $ipAddress = null, $fingerprint = null)
    {
        $exists = false;
        if (!is_null($userId) || !is_null($ipAddress) || !is_null($fingerprint)) {
            $exists = SurveillanceManager::where(function ($query) use ($fingerprint) {
                if (!is_null($fingerprint)) {
                    $query->where('type', 'fingerprint')->where('value', $fingerprint)->where("access_blocked", 1);
                }
            })->orWhere(function ($query) use ($userId) {
                if (!is_null($userId)) {
                    $query->where('type', 'userid')->where('value', $userId)->where("access_blocked", 1);
                }
            })->orWhere(function ($query) use ($ipAddress) {
                if (!is_null($ipAddress)) {
                    $query->where('type', 'ip')->where('value', $ipAddress)->where("access_blocked", 1);
                }
            })->exists();
        } else {
            $exists = SurveillanceManager::where("type", $this->getType())
                ->where("value", $this->getValue())
                ->where("access_blocked", 1)->exists();
        }
        return $exists;
    }

    /**
     * Checks if surveillance is enabled ot not
     *
     * @param [int] $userId
     * @param [string] $ipAddress
     * @param [string] $fingerprint
     * @return boolean
     */
    public function isSurveillanceEnabled($userId = null, $ipAddress = null, $fingerprint = null)
    {
        $exists = false;

        if (!is_null($userId) || !is_null($ipAddress) || !is_null($fingerprint)) {
            $exists = SurveillanceManager::where(function ($query) use ($fingerprint) {
                if (!is_null($fingerprint)) {
                    $query->where('type', 'fingerprint')->where('value', $fingerprint)->where("surveillance_enabled", 1);
                }
            })->orWhere(function ($query) use ($userId) {
                if (!is_null($userId)) {
                    $query->where('type', 'userid')->where('value', $userId)->where("surveillance_enabled", 1);
                }
            })->orWhere(function ($query) use ($ipAddress) {
                if (!is_null($ipAddress)) {
                    $query->where('type', 'ip')->where('value', $ipAddress)->where("surveillance_enabled", 1);
                }
            })->exists();
        } else {
            $exists = SurveillanceManager::where("type", $this->getType())
                ->where("value", $this->getValue())
                ->where("surveillance_enabled", 1)->exists();
        }
        return $exists;
    }

    /**
     * Get a single surveillance record by its id from database
     *
     * @param [int] $id
     * @return void
     */
    public function getRecordById(int $id)
    {
        return SurveillanceManager::findOrFail($id);
    }

    /**
     * Return a paginated and filtered list of the surveillance records
     *
     * @param [array] $filters
     * @return array
     */
    public function getPaginatedAndFilteredRecords($filters = array())
    {
        $query = SurveillanceManager::where("id", ">=", 1);
        if (!empty($filters["type"])) {
            $query->where("type", $filters["type"]);
        }
        if (!empty($filters["status"]) && $filters["status"] == "enabled") {
            $query->where("surveillance_enabled", 1);
        }
        if (!empty($filters["status"]) && $filters["status"] == "blocked") {
            $query->where("access_blocked", 1);
        }
        if (!empty($filters["status"]) && $filters["status"] == "disabled") {
            $query->whereNull("surveillance_enabled")->orWhere("surveillance_enabled", 0);
        }
        if (!empty($filters["status"]) && $filters["status"] == "unblocked") {
            $query->whereNull("access_blocked")->orWhere("access_blocked", 0);
        }
        if (!empty($filters["search"])) {
            $query->where("value", "LIKE", "%" . $filters["search"] . "%");
        }
        //orderBy
        if (!empty($filters["search"])) {
            $query->orderBy("value");
        } else {
            $query->orderBy("id", "desc");
        }

        return $query->paginate(!empty($filters["limit"]) ? $filters["limit"] : 10, ["*"], 'page', !empty($filters["page"]) ? $filters["page"] : 1)->toArray();
    }

    /**
     * Delete surveillance record by its id from database
     *
     * @param [int] $id
     * @return bool
     */
    public function removeRecordById(int $id)
    {
        return SurveillanceManager::destroy($id);
    }

    /**
     * Get count of total surveillance records from database
     * @return int
     */
    public function totalRecords()
    {
        return SurveillanceManager::count();
    }
}
