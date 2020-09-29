<?php

namespace Neelkanth\Laravel\Surveillance\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Neelkanth\Laravel\Surveillance\Interfaces\SurveillanceManagerInterface;
use Neelkanth\Laravel\Surveillance\Interfaces\SurveillanceLogInterface;

class SurveillanceMiddleware
{

    protected $surveillanceManager;
    protected $surveillanceLog;

    public function __construct(SurveillanceManagerInterface $manager, SurveillanceLogInterface $log)
    {
        $this->surveillanceManager = $manager;
        $this->surveillanceLog = $log;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //Fingerprint
        $fingerprintHeaderKey = config("surveillance.fingerprint-header-key");
        $fingerprint = $request->header($fingerprintHeaderKey);
        //User id
        $userId = Auth::id();
        //IP Address
        $ipAddress = $request->ip();
        //Check if any of the above is blocked
        $isAccessBlocked = $this->surveillanceManager->isAccessBlocked($userId, $ipAddress, $fingerprint);
        if ($isAccessBlocked) {
            abort(403);
        } else {
            //Check if surveillance is enabled on any of the above
            $isSurveillanceEnabled = $this->surveillanceManager->isSurveillanceEnabled($userId, $ipAddress, $fingerprint);
            
            if ($isSurveillanceEnabled) {
                //Log the request
                $this->surveillanceLog->makeLogFromRequest($request)->writeLog();
            }
        }
        return $next($request);
    }
}
