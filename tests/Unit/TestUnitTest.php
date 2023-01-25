<?php

namespace Test\Unit;

use Core\Test;
use PHPUnit\Framework\TestCase;

class TestUnitTest extends TestCase
{
    public function test_call_method_foo()
    {
        $teste = Test::foo();

        $this->assertEquals('112233', $teste);
    }
}
