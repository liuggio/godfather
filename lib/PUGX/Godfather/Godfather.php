<?php

namespace PUGX\Godfather;

use PUGX\Godfather\Container\ContainerInterface;

class Godfather implements StrategistInterface
{
    /** @var \Symfony\Component\DependencyInjection\TaggedContainerInterface */
    private $container;
    /** @var string */
    private $servicePrefix;
    /** @var ServiceNameConverter */
    private $converter;

    public function __construct(ContainerInterface $container, $servicePrefix = '', ServiceNameConverter $serviceNameConverter = null)
    {
        $this->container = $container;
        $this->servicePrefix = $servicePrefix;
        if (null === $serviceNameConverter) {
            $serviceNameConverter = new ServiceNameConverter();
        }
        $this->converter = $serviceNameConverter;
    }

    /**
     * {@inheritDoc}
     */
    public function addStrategy($contextName, $contextKey, $strategyServiceId)
    {
        // each context has a service
        $contextKeyServiceId = $this->converter->serviceNameConverter($contextKey);
        // prefix.context_name.key_service
        $contextKeyServiceId = $this->converter->getServiceNamespace($this->servicePrefix, array($contextName, $contextKeyServiceId));

        if ($this->container->has($strategyServiceId)) {
            return $this->container->setAlias($contextKeyServiceId, $strategyServiceId);
        }

        throw new \InvalidArgumentException(sprintf('You have requested a non-existent service "%s".', $strategyServiceId));
    }

    /**
     * {@inheritDoc}
     */
    public function getStrategy($contextName, $object)
    {
        // prefix.context_name
        $contextServiceId = $this->converter->getServiceNamespace($this->servicePrefix, $contextName);
        // get the correct strategy service by the object
        $strategy = $this->container->get($contextServiceId);
        $strategy = $strategy->getStrategyName($object);
        // get service namespace
        $strategyAlias = $this->converter->getServiceNamespace($this->servicePrefix, array($contextName, $strategy));
        if (!$this->container->has($strategyAlias)) {
            $strategyAlias = $this->container->get($contextServiceId)->getFallbackStrategy();
        }

        return  $this->container->get($strategyAlias);
    }

    /**
     * Magic for getStrategy.
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $args)
    {
        $prefix = substr($method, 0, 3);
        if (strcmp($prefix, 'get') != 0) {
            throw new \BadMethodCallException($method);
        }
        $methodName = substr($method, 3);
        $contextName = $this->converter->serviceNameConverter($methodName);

        return call_user_func_array(array($this, 'getStrategy'), array_merge(array($contextName), $args));

    }
}
