<?php

namespace PUGX\Godfather\Context;

interface ContextInterface
{
    /**
     * Get the correct strategy given the object.
     *
     * @param mixed $object
     *
     * @return string
     */
    public function getStrategyName($object);

    /**
     * @return mixed
     */
    public function getFallbackStrategy();
}
