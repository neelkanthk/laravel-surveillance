<?php

namespace Neelkanth\Laravel\Surveillance\Facades;

use Illuminate\Support\Facades\Facade;

class Surveillance extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'surveillance';
    }
}