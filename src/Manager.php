<?php

namespace Annotation\Scannable;

use Annotation\Scannable\Contracts\Scanned;
use BadMethodCallException;
use Closure;
use Illuminate\Support\Traits\Macroable;

class Manager implements Scanned
{
    use Macroable {
        __call as macroCall;
    }

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

    /**
     * Dynamically handle calls the instance.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }

}
