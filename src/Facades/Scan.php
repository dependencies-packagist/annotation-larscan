<?php

namespace Annotation\Scannable\Facades;

use Annotation\Scannable\Contracts\Scanner;
use Annotation\Scannable\Manager;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void scanning(string $abstract, mixed ...$payload)
 * @method static Collection using(string $abstract, Closure $callback)
 *
 * @see Scanner
 * @see Manager
 */
class Scan extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Scanner::class;
    }

}
