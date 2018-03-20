<?php declare(strict_types=1);

namespace IgniTest\Unit\Container\DependencyResolver;

use Igni\Container\DependencyResolver\Argument;
use PHPUnit\Framework\TestCase;

final class ArgumentTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        $argument = new Argument('testname', 'string', false, 'default');
        self::assertInstanceOf(Argument::class, $argument);
        self::assertSame('testname', $argument->getName());
        self::assertSame('string', $argument->getType());
        self::assertFalse($argument->isOptional());
        self::assertSame('default', $argument->getDefaultValue());
    }
}
