<?php

namespace Godfather;

class Context
{

    private $name;
    private $fallback;
    private $interface;
    private $strategies = array();

    public function __construct($name, $interface = null, $fallback = null)
    {
        $this->name = $name;
        $this->interface = $interface;

        if (null !== $fallback && !$this->isRespectingInterface($fallback)) {
            throw new \InvalidArgumentException();
        }

        $this->fallback = $fallback;
    }

    public function addStrategy($key, $strategy)
    {
        $key = $this->convertToKey($key);

        if (!$this->isRespectingInterface($strategy)) {
            throw new \InvalidArgumentException();
        }

        $this->strategies[$key] = $strategy;
    }

    public function getStrategy($key)
    {
        $key = $this->convertToKey($key);

        if (!isset($this->strategies[$key]) && (null !== $this->fallback)) {
            return $this->fallback;
        }

        return $this->strategies[$key];
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $fallback
     */
    public function setFallback($fallback)
    {
        $this->fallback = $fallback;
    }

    /**
     * @return string
     */
    public function getFallback()
    {
        return $this->fallback;
    }

    /**
     * @param mixed $interface
     */
    public function setInterface($interface)
    {
        $this->interface = $interface;
    }

    /**
     * @return mixed
     */
    public function getInterface()
    {
        return $this->interface;
    }

    /**
     * Return true if the object is an instance of the interface.
     *
     * @param mixed $object
     *
     * @return Boolean
     */
    private function isRespectingInterface($object)
    {
        if (null !== $this->interface) {
            return ($object instanceof $this->interface);
        }

        return true;
    }

    /**
     * Convert an object into a key.
     *
     * @param $mixed
     *
     * @return string
     */
    private function convertToKey($mixed)
    {
        if (is_object($mixed)) {
            return get_class($mixed);
        }

        try {
            $mixed = (string)$mixed;
        } catch (\Exception $e) {
            $mixed = gettype($mixed);
        }

        return $mixed;
    }

}