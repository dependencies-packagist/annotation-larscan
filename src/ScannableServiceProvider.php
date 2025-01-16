<?php

namespace Annotation\Scannable;

use Annotation\Scannable\Contracts\Scanned;
use Annotation\Scannable\Facades\Scan;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use ReflectionAttribute;
use ReflectionClass;
use Rfc\Scannable\Contracts\Scannable;

class ScannableServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(Scanned::class, Manager::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->booted(function (Application $application) {
            foreach ($application->getLoadedProviders() as $provider => $state) {
                array_map(function ($attribute) use ($provider) {
                    Scan::scanning(
                        $attribute->getName(),
                        $attribute->newInstance(),
                        $attribute,
                        $provider
                    );
                }, is_subclass_of($provider, Scannable::class) ? $this->getAttributes($provider) : []);
            }
        });
    }

    private function getAttributes($provider)
    {
        return with(new ReflectionClass($provider), function ($reflection) {
            return $reflection->getAttributes(Scannable::class, ReflectionAttribute::IS_INSTANCEOF);
        });
    }

}
