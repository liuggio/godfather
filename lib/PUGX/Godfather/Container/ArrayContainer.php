<?php

namespace PUGX\Godfather\Container;

class ArrayContainer implements ContainerInterface
{
    private $container = array();
    private $alias = array();
    /**
     * {@inheritDoc}
     */
    public function setAlias($alias, $id)
    {
        $this->alias[$alias] = $id;
    }

    /**
     * {@inheritDoc}
     */
    public function hasAlias($id)
    {
        return isset($this->alias[$id]);
    }

    /**
     * {@inheritDoc}
     */
    public function set($id, $service)
    {
        if (!isset($this->container[$id]) && isset($this->alias[$id])) {
            $id = $this->alias[$id];
        }
        $this->container[$id] = $service;
    }

    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return isset($this->container[$id]) || isset($this->alias[$id]);
    }

    /**
     * {@inheritDoc}
     */
    public function get($id)
    {
        if (isset($this->alias[$id])) {
            $id = $this->alias[$id];
        }

        return $this->container[$id];
    }

}
