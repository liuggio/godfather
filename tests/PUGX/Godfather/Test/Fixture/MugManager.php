<?php

namespace PUGX\Godfather\Test\Fixture;

class MugManager implements ManagerInterface
{
    public function getName()
    {
        return 'echo-mug';
    }
}
