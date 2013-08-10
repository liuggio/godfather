<?php

namespace PUGX\Godfather;

Interface StrategyInterface
{
    /**
     * Create a new context, strategy interface and fallback are optional.
     *
     * @param string $contextName
     * @param string $strategyInterface
     * @param mixed  $fallBackStrategy
     *
     * @return Godfather $this
     */
    function addContext($contextName, $strategyInterface = null, $fallBackStrategy = null);

    /**
     * Get the contexts array
     *
     * @return ContextInterface[]
     */
    function getContexts();

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
    function addStrategy($contextName, $contextKey, $strategy);

    /**
     * Get the strategy for the key ($contextName, $context).
     *
     * @param string $contextName
     * @param mixed $context
     *
     * @return mixed
     */
    function getStrategy($contextName, $context);
}

