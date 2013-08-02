<?php

namespace Godfather\Test;

use Godfather\Context;
use Godfather\Test\Fixture\StandardCartItem;
use Godfather\Test\Fixture\Entity\Ticket;
use Godfather\Test\Fixture\TicketCartItem;


Class ContextTest extends \PHPUnit_Framework_TestCase
{

    public function testExceptionAddContextWithFallBack()
    {
        $context = new Context('cart', 'Godfather\Test\Fixture\CartItemInterface', new StandardCartItem());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testExceptionAddContextNoRespectForTheInterface()
    {
        $context = new Context('cart', 'Godfather\Test\Fixture\CartItemInterface', new \StdClass);
    }

    public function testStrategy()
    {
        $context = new Context('cart', 'Godfather\Test\Fixture\CartItemInterface', new StandardCartItem());
        $entity = new Ticket();
        $ticketStrategy = new TicketCartItem();
        $context->addStrategy($entity, $ticketStrategy);

        $this->assertEquals($context->getStrategy($entity), $ticketStrategy);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testStrategyNoRespectForTheInterface()
    {
        $context = new Context('cart', 'Godfather\Test\Fixture\CartItemInterface', new StandardCartItem());
        $entity = new Ticket();
        $ticketStrategy = new \StdClass();
        $context->addStrategy($entity, $ticketStrategy);
    }

}

