<?php

namespace Mohamed7sameer\CountriesLaravel\Package;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

class Facade extends IlluminateFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mohamed7sameer.countries';
    }
}
