<?php

namespace Manager;

class DefaultManager implements ManagerInterface
{
    public function getName()
    {
        return 'echo-unmanaged class';
    }
}
