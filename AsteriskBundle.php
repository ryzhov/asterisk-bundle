<?php

namespace Ryzhov\Bundle\AsteriskBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ryzhov\Bundle\AsteriskBundle\DependencyInjection\Compiler\EventHandlerPass;

class AsteriskBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new EventHandlerPass());
    }
}
