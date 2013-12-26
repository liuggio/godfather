<?php

namespace PUGX\Godfather\Test;

use PUGX\Godfather\Godfather;

Class GodfatherTest extends \PHPUnit_Framework_TestCase
{
    private $containerMock;
    private $godfather;

    public function setUp()
    {
        $this->containerMock = $this->getMock('PUGX\Godfather\Container\ContainerInterface');
        $this->godfather = new Godfather($this->containerMock, 'prefix');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddStrategy()
    {
        $serviceId = 'service.id';

        $this->containerMock->expects($this->once())
            ->method('has')
            ->with($this->equalTo('service.id'))
            ->will($this->returnValue(false));

        $this->godfather->addStrategy('context_name', 'contextKey', $serviceId);
    }

    public function testAddStrategyWithException()
    {
        $serviceId = 'service.id';

        $this->containerMock->expects($this->once())
            ->method('has')
            ->will($this->returnValue(true));

        $this->containerMock->expects($this->once())
            ->method('setAlias')
            ->with($this->equalTo('prefix.context_name.context_key'), $this->equalTo($serviceId));

        $this->godfather->addStrategy('context_name', 'contextKey', $serviceId);
    }

    public function testGetStrategy()
    {
        $serviceId = 'service.id';

        $contextKeyNormalized = 'context_key';
        $context = $this->getMock('PUGX\Godfather\Context\ContextInterface');
        $context->expects($this->once())
            ->method('getStrategyName')
            ->will($this->returnValue($contextKeyNormalized));

        $this->containerMock->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('prefix.context_name'))
            ->will($this->returnValue($context));
        $this->containerMock->expects($this->once())
            ->method('has')
            ->with($this->equalTo('prefix.context_name.context_key'))
            ->will($this->returnValue(true));
        $this->containerMock->expects($this->at(2))
            ->method('get')
            ->with($this->equalTo('prefix.context_name.context_key'));

        $this->godfather->getStrategy('context_name', 'contextKey', $serviceId);
    }

    public function testGetStrategyShouldReturnFallbackStrategy()
    {
        $serviceId = 'service.id';

        $contextKeyNormalized = 'context_key';
        $context = $this->getMock('PUGX\Godfather\Context\ContextInterface');
        $context->expects($this->once())
            ->method('getStrategyName')
            ->will($this->returnValue($contextKeyNormalized));
        $context->expects($this->once())
            ->method('getFallbackStrategy')
            ->will($this->returnValue("stdClass"));

        $this->containerMock->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('prefix.context_name'))
            ->will($this->returnValue($context));
        $this->containerMock->expects($this->once())
            ->method('has')
            ->with($this->equalTo('prefix.context_name.context_key'))
            ->will($this->returnValue(false));
        $this->containerMock->expects($this->at(2))
            ->method('get')
            ->will($this->returnValue($context));
        $this->containerMock->expects($this->at(2))
            ->method('get')
            ->with('prefix.context_name');

        $this->godfather->getStrategy('context_name', 'contextKey', $serviceId);
    }
}
