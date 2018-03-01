<?php declare(strict_types=1);

namespace IgniTest\Fixtures;

class Bar
{
    public $b;

    public function __construct(BInterface $b)
    {
        $this->b = $b;
    }
}
