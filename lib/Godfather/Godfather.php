<?php

namespace Godfather;

Class Godfather
{
    private $contextClass;
    private $contexts = array();

    public function __construct($contextClass = 'Godfather\Context')
    {
        $this->contextClass = $contextClass;
    }

    public function addContext($contextName, $strategyInterface, $fallBackStrategy = null)
    {
        $this->contexts[$contextName] = $this->createContext($contextName, $strategyInterface, $fallBackStrategy);
    }

    /**
     * @return array
     */
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * Simple Context factory.
     *
     * @param $contextName
     * @param $strategyInterface
     * @param $fallBackStrategy
     *
     * @return Context
     */
    public function createContext($contextName, $strategyInterface = null, $fallBackStrategy = null)
    {
        $class = $this->contextClass;
        return new $class($contextName, $strategyInterface, $fallBackStrategy);
    }

    /**
     * Add a strategy to a context.
     *
     * @param string $contextName
     * @param mixed $contextKey
     * @param mixed $strategy
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function addStrategy($contextName, $contextKey, $strategy)
    {
        if (!isset($this->contexts[$contextName])) {
            $this->contexts[$contextName] = $this->createContext($contextName);
        }
        $this->contexts[$contextName]->addStrategy($contextKey, $strategy);

        return $this;
    }

    /**
     * Get the strategy for the key ($contextName, $context).
     *
     * @param string $contextName
     * @param mixed $context
     *
     * @return mixed
     */
    public function getStrategy($contextName, $context)
    {
        return $this->contexts[$contextName]->getStrategy($context);
    }

}

