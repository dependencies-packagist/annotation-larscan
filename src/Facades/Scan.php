<?php

namespace Annotation\Scannable\Facades;

use Annotation\Scannable\Contracts\Scannable;
use Illuminate\Support\Facades\Facade;

class Scan extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Scannable::class;
    }

}
