<?php

namespace PUGX\Godfather\Test\Context;

use PUGX\Godfather\Context\Context;

Class ContextTest extends \PHPUnit_Framework_TestCase
{
    public function testReturnFallback()
    {
        $context = new Context(new \stdClass());

        $this->assertInstanceOf('\stdClass', $context->getFallbackStrategy());
    }

    public function testGetStrategy()
    {
        $context = new Context(new \stdClass());

        $this->assertEquals('ticket', $context->getStrategyName(new Ticket()));
    }

}

class Ticket
{
}
