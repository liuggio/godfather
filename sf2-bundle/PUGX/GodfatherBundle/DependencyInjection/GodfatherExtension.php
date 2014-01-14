<?php

namespace PUGX\GodfatherBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GodfatherExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        foreach ($config as $name => $instance) {
            $this->addInstance($name, $instance, $container);
        }
    }

    /**
     * Add an instance of Strategies.
     *
     * @param string           $name       The instance name
     * @param array            $parameters Array of parameters.
     * @param ContainerBuilder $container  A ContainerBuilder instance
     */
    protected function addInstance($name, array $parameters, ContainerBuilder $container)
    {
        if ('default' == $name) {
            $prefix = 'godfather';
        } else {
            $prefix = sprintf("godfather.%s", $name);
        }

        $this->getOrCreateDefinition($container, $prefix);

        $contexts = $parameters['contexts'];
        foreach ($contexts as $name => $context) {
            $this->addContext($container, $prefix, $name, $context);
        }
    }

    /**
     * Add a Context.
     *
     * @param ContainerBuilder $container
     * @param string           $prefix
     * @param string           $name
     * @param array            $context
     */
    protected function addContext(ContainerBuilder $container, $prefix, $name, array $context)
    {
        $fallback = null;
        $serviceName = $prefix.'.'.$name;
        $default = 'godfather.context';
        if (isset($context['fallback']) || isset($context['class'])) {
            $fallback = $context['fallback'];
            $context = new Definition($context['class'], array($fallback));
            $container->setDefinition($serviceName, $context);

            return ;
        }

        $container->setAlias($serviceName, $default);
    }

    /**
     * Get or create the instance definition.
     *
     * @param ContainerBuilder $container
     * @param string           $name
     *
     * @return Definition
     */
    protected function getOrCreateDefinition(ContainerBuilder $container, $name = 'default')
    {
        if (!$container->hasDefinition($name)) {
            $definition = $this->createGodFatherDefinition($container, $name);
            $container->setDefinition($name, $definition);
        } else {
            $definition = $container->getDefinition($name);
        }

        return $definition;
    }

    /**
     * Definition Factory
     *
     * @param ContainerBuilder $container
     * @param string           $prefix
     *
     * @return Definition
     */
    protected function createGodFatherDefinition(ContainerBuilder $container, $prefix)
    {
        $container_definition = new Reference('service_container');
        $service_container =  new Definition('%godfather.container.class%', array($container_definition));
        $container->setDefinition(sprintf('godfather_service_container.%s',$prefix), $service_container);

        return new Definition('%godfather.class%', array($service_container, $prefix));
    }


    /**
     * Definition Factory
     *
     * @param ContainerBuilder $container
     * @param string           $prefix
     *
     * @return Definition
     */
    protected function createServiceContainerDefinition(ContainerBuilder $container, $prefix)
    {
        $container =  new Definition('%godfather.class%');

        return new Definition('%godfather.class%', array($container, $prefix));
    }
}
