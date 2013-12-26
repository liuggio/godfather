<?php

namespace PUGX\GodfatherBundle\Tests\DependencyInjection;

use PUGX\GodfatherBundle\DependencyInjection\GodfatherExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class GodfatherExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testDefault()
    {
        $container = new ContainerBuilder();
        $loader = new GodfatherExtension();
        $loader->load(array(array()), $container);
        $this->assertTrue($container->hasDefinition('godfather'), 'The godfather is loaded');
    }

    public function testMinimalWithMultipleContexts()
    {
        $container = new ContainerBuilder();
        $loader = new GodfatherExtension();
        $loader->load(array($this->getMinimalMultipleInstances()), $container);

        $this->assertTrue($container->hasDefinition('godfather'), 'The godfather is loaded');
        $this->assertTrue($container->hasDefinition('godfather.manager'), 'The godfather.manager is loaded');
    }

    public function testContextCreated()
    {
        $container = new ContainerBuilder();
        $loader = new GodfatherExtension();
        $loader->load(array(array('instanceA' => array('contexts' => array('manager' => array())))), $container);

        $this->assertTrue($container->hasDefinition('godfather'), 'The godfather is loaded');
        $this->assertTrue($container->hasDefinition('godfather.instanceA'), 'The godfather is loaded');
        $this->assertTrue($container->hasDefinition('godfather.instanceA.manager'), 'The godfather is loaded');
    }

    public function testFallbackService()
    {
        $container = new ContainerBuilder();
        $loader = new GodfatherExtension();
        $loader->load(array(array('instance' => array('contexts' => array('manager' => array('class'=> '\stdClass', 'fallback' => 'fallback'))))), $container);

        $this->assertTrue($container->hasDefinition('godfather.instance'), 'The godfather is loaded');
        $this->assertTrue($container->hasDefinition('godfather.instance.manager'), 'The godfather is loaded');

        $context = $container->getDefinition('godfather.instance.manager');
        $this->assertEquals('\stdClass', $context->getClass());
        $this->assertContains('fallback', $context->getArguments());
    }

    public function testMultipleInstances()
    {
        $container = new ContainerBuilder();
        $loader = new GodfatherExtension();
        $loader->load(array($this->getFullConfigWithMultipleInstances()), $container);

        $this->assertFalse($container->hasDefinition('godfather.default'), 'default shouldn\'t be created');
        $this->assertTrue($container->hasDefinition('godfather'), 'The godfather is loaded');
        $this->assertTrue($container->hasDefinition('godfather.manager'));
        $this->assertTrue($container->hasDefinition('godfather.instance1'));
        $this->assertTrue($container->hasDefinition('godfather.instance1.manager'));
    }

    protected function getFullConfigWithMultipleInstances()
    {
        $yaml = <<<EOF
default:
    contexts:
        manager:
            fallback: fallback0
instance1:
    contexts:
        manager:
            fallback: fallback1
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }


    protected function getMinimalMultipleInstances()
    {
        $yaml = <<<EOF
default:
    contexts:
        manager: ~
instance1:
    contexts:
        manager: ~
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }
}
