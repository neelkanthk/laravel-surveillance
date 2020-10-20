<?php

namespace Neelkanth\Laravel\Surveillance\Implementations;

use Neelkanth\Laravel\Surveillance\Interfaces\SurveillanceLogInterface;
use Neelkanth\Laravel\Surveillance\Models\SurveillanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveillanceLogRepository implements SurveillanceLogInterface
{
    /**
     * The log to write
     *
     * @var [array/object]
     */
    protected $log;

    /**
     * Set the log to write
     *
     * @param [array/object] $log
     * @return void
     */
    public function setLogToWrite($log)
    {
        $this->log = $log;
        return $this;
    }

    /**
     * Get the log to write
     *
     * @return void
     */
    public function getLogToWrite()
    {
        return $this->log;
    }

    /**
     * Retrieve the data from Request for logging
     *
     * @param Request $request
     * @return void
     */
    public function makeLogFromRequest(Request $request)
    {
        $fingerprintHeaderKey = config("surveillance.fingerprint-header-key");
        $cookies = $request->cookies->all();
        $files = $request->allFiles();
        $session = $request->session()->all();
        $this->log = [
            "fingerprint" => $request->header($fingerprintHeaderKey),
            "userid" => Auth::id(),
            "ip" => $request->ip(),
            "url" => $request->fullUrl(),
            "user_agent" => $request->userAgent(),
            "cookies" => !empty($cookies) ? json_encode($cookies) : null,
            "files" => !empty($files) ? json_encode($files) : null,
            "session" => !empty($session) ? json_encode($session) : null
        ];
        return $this;
    }

    /**
     * Write the log in the database
     *
     * @param [array] $dataToLog
     * @return SurveillanceLog
     */
    public function writeLog($dataToLog = null)
    {
        if (!is_null($dataToLog)) {
            $this->setLogToWrite($dataToLog);
        }
        $log = $this->getLogToWrite();
        if (!empty($log) && is_array($log)) {
            $surveillanceLog = new SurveillanceLog();
            $surveillanceLog->fingerprint = $log["fingerprint"];
            $surveillanceLog->userid = $log["userid"];
            $surveillanceLog->ip = $log["ip"];
            $surveillanceLog->url = $log["url"];
            $surveillanceLog->user_agent = $log["user_agent"];
            $surveillanceLog->cookies = $log["cookies"];
            $surveillanceLog->files = $log["files"];
            $surveillanceLog->session = $log["session"];
            $surveillanceLog->save();
            return $surveillanceLog;
        }
    }

    /**
     * Return a paginated and filtered list of the logs
     *
     * @param [array] $filters
     * @return array
     */
    public function getPaginatedAndFilteredLogs($filters = array())
    {
        $query = SurveillanceLog::where("id", ">=", 1);
        if (!empty($filters["search"])) {
            $query->where("ip", "LIKE", "%" . $filters["search"] . "%")
                ->orWhere("userid", "LIKE", "%" . $filters["search"] . "%")
                ->orWhere("fingerprint", "LIKE", "%" . $filters["search"] . "%")
                ->orWhere("url", "LIKE", "%" . $filters["search"] . "%")
                ->orWhere("user_agent", "LIKE", "%" . $filters["search"] . "%")
                ->orWhere("cookies", "LIKE", "%" . $filters["search"] . "%")
                ->orWhere("session", "LIKE", "%" . $filters["search"] . "%")
                ->orWhere("files", "LIKE", "%" . $filters["search"] . "%");
        }
        if (!empty($filters["from_datetime"])) {
            $query->where("created_at", ">=", $filters["from_datetime"]);
        }
        if (!empty($filters["to_datetime"])) {
            $query->where("created_at", "<=", $filters["to_datetime"]);
        }
        return $query->orderBy("created_at", "desc")->paginate(!empty($filters["limit"]) ? $filters["limit"] : 10, ["*"], 'page', !empty($filters["page"]) ? $filters["page"] : 1)->toArray();
    }

    /**
     * Delete log by its id from database
     *
     * @param [int] $id
     * @return bool
     */
    public function deleteLogById(int $id)
    {
        return SurveillanceLog::destroy($id);
    }

    /**
     * Get count of total logs from database
     * @return int
     */
    public function totalLogs()
    {
        return SurveillanceLog::count();
    }

    /**
     * Get a single log by its id from database
     *
     * @param [int] $id
     * @return void
     */
    public function getLogById(int $id)
    {
        return SurveillanceLog::findOrFail($id);
    }
}
