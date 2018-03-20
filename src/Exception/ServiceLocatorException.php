<?php declare(strict_types=1);

namespace Igni\Container\Exception;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

class ServiceLocatorException extends RuntimeException implements ContainerExceptionInterface
{
    public static function serviceNotFoundException($name, $context = null): ServiceLocatorException
    {
        return new self("Could not resolve dependency (${name})" . ($context ? " for (${context})" : ""));
    }

    public static function argumentNotResolvedException($name, $service): ServiceLocatorException
    {
        return new self("Could not resolve argument (${name}) for service (${service})");
    }

    public static function emptyServiceDefinition($name): ServiceLocatorException
    {
        return new self("Empty service definition found in (${name}). Did you forgot return statement?");
    }
}
