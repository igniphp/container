<?php declare(strict_types=1);

namespace Igni\Container\ServiceFactory;

use Psr\Container\ContainerInterface;
use Igni\Container\DependencyResolver;
use Igni\Container\ServiceFactory;

class FactoryServiceFactory extends ServiceFactory
{
    public function __invoke(ContainerInterface $container, string $serviceName)
    {
        if ($this->resolver) {
            return ($this->resolver)($container, $serviceName);
        }
        $resolver = new DependencyResolver($container);

        return $resolver->resolve($this->getName(), $this->bindings);
    }
}
