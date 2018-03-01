<?php declare(strict_types=1);

namespace IgniTest\Fixtures;

class Foo implements AInterface, BInterface
{
    public $a;
    public $b;

    public function __construct(A $a, B $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    public function getA()
    {
        return $this->a->getA();
    }

    public function getB()
    {
        return $this->b->getB();
    }
}
