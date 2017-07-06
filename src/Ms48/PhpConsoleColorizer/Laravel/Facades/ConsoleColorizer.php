<?php

namespace Ms48\PhpConsoleColorizer\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class ConsoleColorizer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'consoleColorizer';
    }
}
