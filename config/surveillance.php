<?php

return [

    /*
     * The name of the header to be used for browser fingerprint
     */
    "fingerprint-header-key" => "fingerprint",

    /*
     *  This class is responsible enabling, disabling, blocking and unblocking.
     *  To override the default functionality extend the below class and provide its name here.
     */
    "manager-repository" => 'Neelkanth\Laravel\Surveillance\Implementations\SurveillanceManagerRepository',

    /*
     *  This class is responsible for logging the surveillance enabled requests
     *  To override the default functionality extend the below class and provide its name here.
     */
    "log-repository" => 'Neelkanth\Laravel\Surveillance\Implementations\SurveillanceLogRepository',

    /*
     *  The types which are allowed currently.
     *  DO NOT MODIFY THESE
     */
    "allowed-types" => ["userid", "ip", "fingerprint"]
];
