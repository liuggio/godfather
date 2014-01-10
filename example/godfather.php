<?php

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Entity', __DIR__);
$loader->add('Manager', __DIR__);

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use PUGX\Godfather\Container\DependencyInjection\CompilerPass;
use PUGX\GodfatherBundle\DependencyInjection\GodfatherExtension;

/**
 * Random.
 *
 * @return \Entity\Mug|\Entity\Tshirt|stdClass
 */
function getRandomEntityProduct()
{
    switch (rand(0,2)) {
        case 0:
            return new Entity\Mug();
        case 1:
            return new Entity\Tshirt();
        case 2:
            return new \stdClass();
    }
}

// loading config
$config = array(
    'default' => array(
        'contexts' => array(
            'manager' => array(
                'fallback' => 'manager.default'
            )
        )
    )
);
$container = new ContainerBuilder();
$loader = new GodfatherExtension();
$loader->load(array($config), $container);
// loading strategies
$loader = new YamlFileLoader($container, new FileLocator(__DIR__));
$loader->load('services.yml');
// loading tagged services
$container->addCompilerPass(new CompilerPass());
$container->compile();

// working with strategy
$product = getRandomEntityProduct();
// getting the correct manager
$strategy = $container->get('godfather')->getManager($product);

echo "Random entity class\t".get_class($product).PHP_EOL;
echo "   the manager class\t".get_class($strategy).PHP_EOL;
echo "   manager::getName()\t".$strategy->getName().PHP_EOL.PHP_EOL;
echo "Please run it again the result may change :) ".PHP_EOL.PHP_EOL;
