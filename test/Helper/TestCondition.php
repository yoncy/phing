<?php

namespace Phing\Test\Helper;

use Phing\Condition\ConditionInterface;

class TestCondition implements ConditionInterface
{
    private $foo = null;

    public function setFoo($value)
    {
        $this->foo = $value;
    }

    public function evaluate()
    {
        return ($this->foo == "bar");
    }
}
