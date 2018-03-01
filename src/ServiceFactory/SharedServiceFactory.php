<?php declare(strict_types=1);

namespace Igni\Container\ServiceFactory;

use Psr\Container\ContainerInterface;

class SharedServiceFactory extends FactoryServiceFactory
{
    private $instance;

    public function __invoke(ContainerInterface $container, string $serviceName)
    {
        if ($this->instance) {
            return $this->instance;
        }

        return $this->instance = parent::__invoke($container, $serviceName);
    }
}
