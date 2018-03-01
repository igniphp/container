<?php declare(strict_types=1);

namespace Igni\Container;

use Igni\Container\Exception\ServiceLocatorException;
use Igni\Container\ServiceFactory\FactoryServiceFactory;
use Igni\Container\ServiceFactory\SharedServiceFactory;
use Psr\Container\ContainerInterface;
use Closure;

class ServiceLocator implements ContainerInterface
{
    /** @var ServiceFactory[] */
    private $services = [];

    public function get($id, string $context = '')
    {
        if (!isset($this->services[$id])) {
            throw ServiceLocatorException::serviceNotFoundException($id);
        }
        /** @var ServiceFactory $result */
        $result = $this->services[$id][0];
        if ($context !== '') {
            /** @var ServiceFactory $service */
            foreach ($this->services[$id] as $service) {
                if ($service->getContext() === $context) {
                    $result = $service;
                    break;
                }
            }
        }
        if ($result instanceof ServiceFactory) {
            return $result($this, $id);
        }

        return $result;
    }

    public function factory(string $id, Closure $definition = null): ServiceFactory
    {
        if (!isset($this->services[$id])) {
            $this->services[$id] = [];
        }
        return $this->services[$id][] = new FactoryServiceFactory($id, $definition);
    }

    public function share(string $id, Closure $definition = null): ServiceFactory
    {
        if (!isset($this->services[$id])) {
            $this->services[$id] = [];
        }
        return $this->services[$id][] = new SharedServiceFactory($id, $definition);
    }

    public function set(string $id, $service): void
    {
        if (!isset($this->services[$id])) {
            $this->services[$id] = [];
        }

        $this->services[$id][] = $service;
    }

    public function has($id, string $context = ''): bool
    {
        if ($context === '') {
            return !empty($this->services[$id]);
        }

        if (empty($this->services[$id])) {
            return false;
        }

        /** @var ServiceFactory $service */
        foreach ($this->services[$id] as $service) {
            if ($service->getContext() === $context) {
                return true;
            }
        }

        return false;
    }
}
