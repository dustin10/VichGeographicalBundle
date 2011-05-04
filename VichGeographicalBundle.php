<?php

namespace Vich\GeographicalBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vich\GeographicalBundle\DependencyInjection\Compiler\ValidateExtensionConfigurationPass;

/**
 * VichGeographicalBundle.
 * 
 * @author Dustin Dobervich <ddobervich@gmail.com>
 */
class VichGeographicalBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        
        $container->addCompilerPass(new ValidateExtensionConfigurationPass());
    }
}