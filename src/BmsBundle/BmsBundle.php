<?php

namespace BmsBundle;

use BmsBundle\DependencyInjection\Compiler\FOSUserOverridePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BmsBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }
    
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FOSUserOverridePass());
    }
}
