<?php

namespace Annotation\Scannable\Annotations;

use Annotation\Scannable\Contracts\Scannable;
use Attribute;
use Composer\Autoload\ClassLoader;
use ReflectionClass;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Scan implements Scannable
{
    protected ClassLoader $classLoader;

    public function __construct(
        protected ReflectionClass|array|string $reflectionClass = []
    )
    {
        $this->setReflectionClass($reflectionClass);
    }

    /**
     * @return array<string,ReflectionClass>
     */
    public function getReflectionClass(): array
    {
        return $this->reflectionClass;
    }

    /**
     * @param ReflectionClass|array|string $reflectionClass
     * @return static
     */
    public function setReflectionClass(ReflectionClass|array|string $reflectionClass): static
    {
        $this->reflectionClass = array_reduce(is_array($reflectionClass) ? $reflectionClass : [$reflectionClass], function (array $reflectionClasses, $reflectionClass) {
            if (is_string($reflectionClass) && class_exists($reflectionClass)) {
                $reflectionClass = new ReflectionClass($reflectionClass);
            }
            if ($reflectionClass instanceof ReflectionClass) {
                $reflectionClasses[$reflectionClass->getName()] = $reflectionClass;
            }
            return $reflectionClasses;
        }, []);
        return $this;
    }

    public function getClassLoader(): ClassLoader
    {
        foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $registeredLoader) {
            if (str_starts_with($vendorDir, base_path())) {
                return $this->classLoader = $registeredLoader;
            }
        }
        return new ClassLoader();
    }

}
