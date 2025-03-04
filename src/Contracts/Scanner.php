<?php

namespace Annotation\Scannable\Contracts;

use Closure;
use Illuminate\Support\Collection;

interface Scanner
{
    public function scanning(string $abstract, mixed ...$payload): void;

    public function using(string $abstract, Closure $callback): Collection;

}
