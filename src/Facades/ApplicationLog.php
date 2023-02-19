<?php

namespace Brunocfalcao\Logger\Facades;

use Brunocfalcao\Logger\ApplicationLog as LoggerClass;
use Illuminate\Support\Facades\Facade;

class ApplicationLog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return app(LoggerClass::class);
    }
}
