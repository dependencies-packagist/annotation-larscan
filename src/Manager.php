<?php

namespace Annotation\Scannable;

use Annotation\Scannable\Contracts\Scanned;
use BadMethodCallException;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;

class Manager implements Scanned
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * All the scan callbacks.
     *
     * @var array<string,array>
     */
    protected array $scanCallback = [];

    /**
     * @param string $abstract
     * @param mixed ...$payload
     * @return void
     */
    public function scanning(string $abstract, mixed ...$payload): void
    {
        if (!array_key_exists($abstract, $this->scanCallback)) {
            $this->scanCallback[$abstract] = [];
        }
        $this->scanCallback[$abstract][] = $payload;
    }

    /**
     * @param string $abstract
     * @param Closure $callback
     * @return Collection
     */
    public function using(string $abstract, Closure $callback): Collection
    {
        return $this->fireCallback(array_map(function ($payload) use ($callback) {
            return [$callback, $payload];
        }, $this->scanCallback[$abstract] ?? []));
    }

    /**
     * @param array $callbacks
     * @return Collection
     */
    protected function fireCallback(array $callbacks): Collection
    {
        $responses = [];
        foreach ($callbacks as [$callback, $arguments]) {
            $response = call_user_func_array($callback, $arguments);

            if ($response === false) {
                break;
            }

            $responses[] = $response;
        }
        return collect($responses);
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
