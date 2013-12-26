<?php

namespace PUGX\Godfather\Container;

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

interface ContainerInterface
{
    /**
     * Sets an alias for an existing service.
     *
     * @param string $alias The alias to create
     * @param string $id    The service to alias
     *
     * @throws InvalidArgumentException if the id is not a string or an Alias
     * @throws InvalidArgumentException if the alias is for itself
     *
     * @api
     */
    public function setAlias($alias, $id);

    /**
     * Returns true if an alias exists under the given identifier.
     *
     * @param string $id The service identifier
     *
     * @return Boolean true if the alias exists, false otherwise
     *
     * @api
     */
    public function hasAlias($id);

    /**
     * Sets a service.
     *
     * @param string $id      The service identifier
     * @param object $service The service instance
     *
     * @throws RuntimeException         When trying to set a service in an inactive scope
     * @throws InvalidArgumentException When trying to set a service in the prototype scope
     *
     * @api
     */
    public function set($id, $service);

    /**
     * Returns true if the given service is defined.
     *
     * @param string $id The service identifier
     *
     * @return Boolean true if the service is defined, false otherwise
     *
     * @api
     */
    public function has($id);

    /**
     * Gets a service.
     *
     * If a service is defined both through a set() method and
     * with a get{$id}Service() method, the former has always precedence.
     *
     * @param string $id The service identifier
     *
     * @return object The associated service
     *
     * @throws InvalidArgumentException if the service is not defined
     * @throws ServiceNotFoundException When the service is not defined
     *
     * @api
     */
    public function get($id);
}
