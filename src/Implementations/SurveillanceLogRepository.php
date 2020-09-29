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
}
