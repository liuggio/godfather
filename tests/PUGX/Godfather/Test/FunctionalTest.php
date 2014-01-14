<?php

namespace PUGX\Godfather\Test;

use PUGX\Godfather\Container\ArrayContainer;
use PUGX\Godfather\Container\SymfonyContainerBuilder;
use PUGX\Godfather\Godfather;
use PUGX\Godfather\Test\Fixture\MugProduct;
use PUGX\Godfather\Test\Fixture\TshirtProduct;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testStrategyUsage($godfather, $contextName, $contextKey, $productObject, $strategyServiceId, $assertion)
    {
        $godfather->addStrategy($contextName, $contextKey, $strategyServiceId);

        $strategy = $godfather->getStrategy($contextName, $productObject);

        $this->assertEquals($assertion, $strategy->getName());
    }

    public function provider()
    {
        return array(
            'SymfonyContainer with the Mug'    => array($this->createSymfonyGodfather(), 'manager', 'pugx.godfather.test.fixture.mug_product',    new MugProduct(),    'mug_manager'   , 'echo-mug'),
            'SymfonyContainer with the Tshirt' => array($this->createSymfonyGodfather(), 'manager', 'pugx.godfather.test.fixture.tshirt_product', new TshirtProduct(), 'tshirt_manager', 'echo-tshirt'),
            'ArrayContainer with the Mug'      => array($this->createArrayGodfather(),   'manager', 'pugx.godfather.test.fixture.mug_product',    new MugProduct(),    'mug_manager'   , 'echo-mug'),
            'ArrayContainer with the Tshirt'   => array($this->createArrayGodfather(),   'manager', 'pugx.godfather.test.fixture.tshirt_product', new TshirtProduct(), 'tshirt_manager', 'echo-tshirt'),
        );
    }

    /**
     * @dataProvider fallbackProvider
     */
    public function testFallbackStrategy()
    {
        $godfather = $this->createSymfonyGodfather();

        $strategy = $godfather->getStrategy('manager', new \stdClass());
        $this->assertEquals('echo-tshirt', $strategy->getName());
    }

    public function fallbackProvider()
    {
        return array(
            array($this->createSymfonyGodfather()),
            array($this->createArrayGodfather())
        );
    }

    private function createArrayGodfather()
    {
        $container = new ArrayContainer();

        $container
            ->set('godfather.manager',  new \PUGX\Godfather\Context\Context('tshirt_manager'));
        $container
            ->set('tshirt_manager', new \PUGX\Godfather\Test\Fixture\TshirtManager());
        $container
            ->set('mug_manager', new \PUGX\Godfather\Test\Fixture\MugManager());

        return new Godfather($container, 'godfather');
    }

    private function createSymfonyGodfather()
    {
        $container = new SymfonyContainerBuilder(new ContainerBuilder());
        $container
            ->register('godfather.manager', '\PUGX\Godfather\Context\Context')
            ->addArgument('tshirt_manager');
        $container
            ->register('tshirt_manager', '\PUGX\Godfather\Test\Fixture\TshirtManager');
        $container
            ->register('mug_manager', '\PUGX\Godfather\Test\Fixture\MugManager');

        return new Godfather($container, 'godfather');
    }
}
