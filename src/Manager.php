<?php

namespace Annotation\Scannable;

use Annotation\Scannable\Contracts\Scanned;
use Closure;
use Illuminate\Support\Traits\Macroable;

class Manager implements Scanned
{
    use Macroable;

    /**
     * All the global scan callbacks.
     *
     * @var array
     */
    protected array $globalScanCallback = [];

    /**
     * All the scan callbacks.
     *
     * @var array<string,array>
     */
    protected array $scanCallback = [];

    /**
     * @param Closure|string $abstract
     * @param mixed ...$payload
     * @return void
     */
    public function scanning(Closure|string $abstract, mixed ...$payload): void
    {
        if ($abstract instanceof Closure) {
            $this->globalScanCallback[] = [$abstract, $payload];
        } else {
            if (!array_key_exists($abstract, $this->scanCallback)) {
                $this->scanCallback[$abstract] = [];
            }
            $this->scanCallback[$abstract][] = $payload;
        }
    }

    /**
     * @param string $abstract
     * @param Closure $callback
     * @return void
     */
    public function using(string $abstract, Closure $callback): void
    {
        $this->fireCallback($this->globalScanCallback);
        $this->fireCallback(array_map(function ($payload) use ($callback) {
            return [$callback, $payload];
        }, $this->scanCallback[$abstract] ?? []));
    }

    /**
     * @param array $callbacks
     * @return void
     */
    protected function fireCallback(array $callbacks): void
    {
        foreach ($callbacks as [$callback, $arguments]) {
            call_user_func_array($callback, $arguments);
        }
    }

}
