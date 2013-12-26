<?php

namespace PUGX\Godfather\Container;

use Symfony\Component\DependencyInjection\ContainerInterface as SymfonyContainerInterface;
use Symfony\Component\DependencyInjection\Alias;

class SymfonyContainerBuilder implements ContainerInterface
{
    private $aliasDefinitions;

    public function __construct(SymfonyContainerInterface $container, $aliasDefinitions = null)
    {
        $this->container = $container;
        $this->aliasDefinitions = $aliasDefinitions;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->container, $method), $args);
    }

    /**
     * Sets an alias for an existing service.
     *
     * @param string        $alias The alias to create
     * @param string|Alias  $id    The service to alias
     *
     * @throws InvalidArgumentException if the id is not a string or an Alias
     * @throws InvalidArgumentException if the alias is for itself
     *
     * @api
     */
    public function setAlias($alias, $id)
    {
        $alias = strtolower($alias);

        if (is_string($id)) {
            $id = new Alias($id);
        } elseif (!$id instanceof Alias) {
            throw new InvalidArgumentException('$id must be a string, or an Alias object.');
        }

        if ($alias === strtolower($id)) {
            throw new InvalidArgumentException(sprintf('An alias can not reference itself, got a circular reference on "%s".', $alias));
        }

        $this->aliasDefinitions[$alias] = $id;
    }

    /**
     * {@inheritDoc}
     */
    public function hasAlias($id)
    {
        return isset($this->aliasDefinitions[strtolower($id)]);
    }

    /**
     * {@inheritDoc}
     */
    public function set($id, $service)
    {
        return $this->container->set($id, $service);
    }

    /**
     * {@inheritDoc}
     */
    public function has($id)
    {
        return ($this->container->has($id) || $this->hasAlias($id));
    }

    /**
     * {@inheritDoc}
     */
    public function get($id)
    {
        if ($this->hasAlias($id)) {
            $id = $this->aliasDefinitions[strtolower($id)];
        }

        return $this->container->get($id);
    }
}
