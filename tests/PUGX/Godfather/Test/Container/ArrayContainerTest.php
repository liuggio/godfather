<?php

namespace PUGX\Godfather\Test;

use PUGX\Godfather\Container\ArrayContainer;

Class ArrayContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testContainer()
    {
        $container = new ArrayContainer();
        $class = new \stdClass();
        $container->set('std', $class);
        $this->assertEquals($container->get('std'), $class);
        $container->setAlias('std2', 'std');
        $this->assertEquals($container->get('std2'), $class);
        $this->assertTrue($container->has('std'));
        $this->assertTrue($container->has('std2'));
    }
}
