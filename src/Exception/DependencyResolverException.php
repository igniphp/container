<?php declare(strict_types=1);

namespace Igni\Container\Exception;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

class DependencyResolverException extends RuntimeException implements ContainerExceptionInterface
{
    public static function forUnresolvableArgumentsNames(): DependencyResolverException
    {
        return new self('Could not resolve arguments names for expression.');
    }

    public static function forNonInstantiableClass(string $className): DependencyResolverException
    {
        return new self("Passed class (${className}) cannot be instantiated.");
    }

    public static function forAutowireFailure($argument, $service): DependencyResolverException
    {
        return new self("Could not auto wire argument (${argument}) for service (${service})");
    }
}
