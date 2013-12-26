<?php

namespace PUGX\Godfather\Container\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

use PUGX\GodfatherBundle\DependencyInjection\GodfatherExtension;
use PUGX\Godfather\ServiceNameConverter;

class CompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('godfather')) {
            return;
        }

        foreach ($container->findTaggedServiceIds('godfather.strategy') as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {

                if (empty($attributes['context_name']) || empty($attributes['context_key'])) {
                    throw new \InvalidArgumentException(sprintf('The class or the name is not defined in the tag for the service "%s"', $id));
                }

                $instanceName = 'godfather';
                if (!empty($attributes['instance']) && $attributes['instance'] != 'default') {
                    $instanceName .= '.'.$attributes['instance'];
                }

                $definition = $container->getDefinition($instanceName);

                $definition->addMethodCall(
                    'addStrategy',
                    array($attributes["context_name"], $attributes["context_key"], $id)
                );
            }
        }
    }
}
