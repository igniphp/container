<?php declare(strict_types=1);

namespace IgniTest\Unit\Container;

use Igni\Container\DependencyResolver;
use Igni\Utils\TestCase;
use IgniTest\Fixtures\A;
use IgniTest\Fixtures\B;
use IgniTest\Fixtures\Foo;
use Psr\Container\ContainerInterface;

class DependencyResolverTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        $container = $this->mock(ContainerInterface::class);

        $instance = new DependencyResolver($container);
        self::assertInstanceOf(DependencyResolver::class, $instance);
    }

    public function testResolve(): void
    {
        $psrContainerMock = $this->mock(ContainerInterface::class);
        $psrContainerMock->shouldReceive('has')->with(A::class)->andReturn(true);
        $psrContainerMock->shouldReceive('has')->with(B::class)->andReturn(true);
        $psrContainerMock->shouldReceive('get')->with(A::class, Foo::class)->andReturn(new A(1));
        $psrContainerMock->shouldReceive('get')->with(B::class, Foo::class)->andReturn(new B(2));

        $dependencyResolver = new DependencyResolver($psrContainerMock);
        $result = $dependencyResolver->resolve(Foo::class);

        self::assertInstanceOf(Foo::class, $result);
    }
}
