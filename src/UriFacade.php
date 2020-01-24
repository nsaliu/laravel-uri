<?php

namespace Nsaliu\Uri;

use Illuminate\Support\Facades\Facade;

class UriFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'URIHelper';
    }
}
