<?php

namespace PUGX\Godfather\Context;

use PUGX\Godfather\ServiceNameConverter;

class Context implements ContextInterface
{
    private $fallbackStrategy;

    public function __construct($fallbackStrategy = null, ServiceNameConverter $serviceNameConverter = null)
    {
        $this->fallbackStrategy = $fallbackStrategy;
        if (null === $serviceNameConverter) {
            $serviceNameConverter = new ServiceNameConverter();
        }
        $this->converter = $serviceNameConverter;
    }

    /**
     * {@inheritDoc}
     */
    public function getStrategyName($object)
    {
        return $this->converter->serviceNameConverter($this->getClassOrType($object));
    }

    /**
     * {@inheritDoc}
     */
    public function getFallbackStrategy()
    {
        return $this->fallbackStrategy;
    }

    private function getClassOrType($object)
    {
        if (is_object($object)) {
            $function = new \ReflectionClass(get_class($object));

            return $function->getShortName();
        }
        try {
            $object = (string) $object;
        } catch (\Exception $e) {
            $object = gettype($object);
        }

        return $object;
    }
}
