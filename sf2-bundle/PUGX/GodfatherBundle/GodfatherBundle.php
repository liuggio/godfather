<?php
namespace PUGX\GodfatherBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PUGX\Godfather\Container\DependencyInjection\CompilerPass;

class GodfatherBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CompilerPass());
    }
}
