<?php

namespace Lia\Addons\Themes\Facades;

use Illuminate\Support\Facades\Facade;

class Theme extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Lia\Addons\Themes\Contracts\ThemeContract::class;
    }
}
