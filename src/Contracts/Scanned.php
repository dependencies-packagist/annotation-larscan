<?php

namespace Annotation\Scannable\Contracts;

use Closure;

interface Scanned
{
    public function scanning(Closure|string $abstract, mixed ...$payload): void;

    public function using(string $abstract, Closure $callback): void;

}
