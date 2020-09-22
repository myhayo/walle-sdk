<?php

namespace Myhayo\Walle\Facades;

use \Illuminate\Support\Facades\Facade;

class Walle extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'walle';
    }
}