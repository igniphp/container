<?php declare(strict_types=1);

namespace IgniTest\Fixtures;

class A implements AInterface
{
    private $a;

    public function __construct($a)
    {
        $this->a = $a;
    }

    public function getA()
    {
        return $this->a;
    }

    public static function create($a)
    {
        return new A($a);
    }
}
