<?php

namespace PUGX\Godfather;

interface StrategistInterface
{
    /**
     * Add a strategy into the container.
     *
     * @param string $contextName       the serviceId of the context
     * @param string $contextKey        the key
     * @param string $strategyServiceId the service to obtain
     *
     * @return
     */
    public function addStrategy($contextName, $contextKey, $strategyServiceId);

    /**
     * Given the object and a context, get the correct strategy service from the container.
     *
     * @param $contextName
     * @param $object
     *
     * @return mixed the service
     */
    public function getStrategy($contextName, $object);

}
