<?php

namespace PUGX\GodfatherBundle\Tests\Container\DependencyInjection;

use PUGX\Godfather\Container\DependencyInjection\CompilerPass;
use Symfony\Component\DependencyInjection\Definition;

class CompilerPassTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessWithoutProviderDefinition()
    {
        $menuPass = new CompilerPass();

        $containerBuilderMock = $this->getContainerBuilderMock();
        $containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('godfather.strategy'))
            ->will($this->returnValue(array()));

        $this->assertNull($menuPass->process($containerBuilderMock));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testProcessWithEmptyClass()
    {
        $containerBuilderMock = $this->getContainerBuilderMock();

        $containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('godfather.strategy'))
            ->will($this->returnValue(array('id' => array('tag1' => array('class' => '')))));

        $menuPass = new CompilerPass();
        $menuPass->process($containerBuilderMock);
    }

    public function testProcessWithClassAndName()
    {
        $containerBuilderMock = $this->getContainerBuilderMock();
        $containerBuilderMock->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(true));
        $containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('godfather.strategy'))
            ->will($this->returnValue(array('id' => array('tag1' => array('context_key' => 'key', 'context_name' => 'name')))));
        $containerBuilderMock->expects($this->once())
            ->method('getDefinition')
            ->with($this->equalTo('godfather'))
            ->will($this->returnValue($this->getMockDefinition()));

        $menuPass = new CompilerPass();
        $menuPass->process($containerBuilderMock);
    }

    public function testProcessWithMultipleInstance()
    {
        $containerBuilderMock = $this->getContainerBuilderMock();
        $containerBuilderMock->expects($this->once())
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('godfather.strategy'))
            ->will($this->returnValue(
                array(
                    'id2' => array('tag2' => array('instance' => 'instance2', 'context_key' => 'key', 'context_name' => 'name'))
                )));

        $containerBuilderMock->expects($this->any())
            ->method('hasDefinition')
            ->will($this->returnValue(false));

        $containerBuilderMock->expects($this->any())
            ->method('setDefinition')
            ->with($this->equalTo('godfather.instance2'));

        $containerBuilderMock->expects($this->any())
            ->method('getDefinition')
            ->will($this->returnValue($this->getMockDefinition()));

        $menuPass = new CompilerPass();
        $menuPass->process($containerBuilderMock);
    }

    private function getContainerBuilderMock()
    {
        $containerBuilderMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $containerBuilderMock->expects($this->at(0))
            ->method('hasDefinition')
            ->with('godfather')
            ->will($this->returnValue(true));

        return $containerBuilderMock;
    }

    private function getMockDefinition()
    {
        $mock = $this->getMockBuilder('Symfony\Component\DependencyInjection\Definition')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->once())
            ->method('addMethodCall')
            ->with($this->equalTo('addStrategy'), $this->isType('array'));

        return $mock;
    }

}
