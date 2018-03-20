<?php declare(strict_types=1);

namespace Igni\Container;

use Igni\Container\DependencyResolver\Argument;
use Igni\Container\Exception\DependencyResolverException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;

final class DependencyResolver
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @var ReflectionClass[]
     */
    private static $reflections = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(string $service, array $bindings = [])
    {
        return $this->resolve($service, $bindings);
    }

    public function resolve(string $className, array $bindings = [])
    {
        $reflection = self::reflectClass($className);

        if (!$reflection->isInstantiable()) {
            throw DependencyResolverException::forNonInstantiableClass($className);
        }

        $constructor = $reflection->getConstructor();

        $values = [];
        if ($constructor) {
            $arguments = $this->parseArguments($reflection->getConstructor());
            $values = $this->resolveArguments($arguments, $bindings, $className);
        }

        return $reflection->newInstanceArgs($values);
    }

    /**
     * @param ReflectionMethod $function
     * @return Argument[]
     */
    private function parseArguments(ReflectionMethod $function): array
    {
        $arguments = [];
        foreach ($function->getParameters() as $parameter) {
            $type = $parameter->getClass() ? $parameter->getClass()->getName() : '';
            $arguments[] = new Argument(
                '$' . $parameter->getName(),
                $type,
                $parameter->isOptional(),
                $parameter->isOptional() ? $parameter->getDefaultValue() : null
            );
        }

        return $arguments;
    }

    /**
     * @param Argument[] $arguments
     * @param array $bindings
     * @param string $context
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function resolveArguments(array $arguments, array $bindings = [], string $context): array
    {
        $values = [];
        foreach ($arguments as $argument) {
            if (null !== $bindings && isset($bindings[$argument->getName()])) {
                $values[] = $bindings[$argument->getName()];
            } elseif ($this->container instanceof ContainerInterface && $argument->getType() && $this->container->has($argument->getType())) {
                $values[] = $this->container->get($argument->getType(), $context);
            } elseif ($argument->isOptional()) {
                $values[] = $argument->getDefaultValue();
            } else {
                throw DependencyResolverException::forAutowireFailure($argument->getName(), $context);
            }
        }

        return $values;
    }

    private static function reflectClass(string $class): ReflectionClass
    {
        if (isset(self::$reflections[$class])) {
            return self::$reflections[$class];
        }

        return self::$reflections[$class] = new ReflectionClass($class);
    }
}
