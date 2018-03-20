<?php declare(strict_types=1);

namespace IgniTest\Unit\Container;

use Igni\Container\ServiceLocator;
use IgniTest\Fixtures;
use PHPUnit\Framework\TestCase;

final class ServiceLocatorTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        self::assertInstanceOf(ServiceLocator::class, new ServiceLocator());
    }

    public function testSharedService(): void
    {
        $serviceLocator = new ServiceLocator();
        $serviceLocator->share(Fixtures\A::class, function() {
            return new Fixtures\A(1);
        });

        $instance = $serviceLocator->get(Fixtures\A::class);

        self::assertInstanceOf(Fixtures\A::class, $instance);
        self::assertSame(1, $instance->getA());

        self::assertSame($serviceLocator->get(Fixtures\A::class), $instance);
        self::assertSame($serviceLocator->get(Fixtures\A::class), $instance);
    }

    public function testSharedServiceWithAutoWiring(): void
    {
        $serviceLocator = new ServiceLocator();
        $serviceLocator->share(Fixtures\A::class, function() {
            return new Fixtures\A(1);
        });
        $serviceLocator->share(Fixtures\B::class, function() {
            return new Fixtures\B(2);
        });
        $serviceLocator->share(Fixtures\Foo::class);

        $fooInstance = $serviceLocator->get(Fixtures\Foo::class);

        self::assertInstanceOf(Fixtures\Foo::class, $fooInstance);
    }

    public function testFactoryService(): void
    {
        $serviceLocator = new ServiceLocator();
        $serviceLocator->factory(Fixtures\A::class, function() {
            return new Fixtures\A(mt_rand(1,10));
        });

        $instance1 = $serviceLocator->get(Fixtures\A::class);
        self::assertInstanceOf(Fixtures\A::class, $instance1);
        $instance2 = $serviceLocator->get(Fixtures\A::class);
        self::assertInstanceOf(Fixtures\A::class, $instance2);

        self::assertNotSame($instance1, $instance2);
    }
}
