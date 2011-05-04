<?php

namespace Vich\GeographicalBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * ValidateExtensionConfigurationPass.
 * 
 * @author Dustin Dobervic <ddobervich@gmail.com>
 */
class ValidateExtensionConfigurationPass implements CompilerPassInterface
{
    /**
     * Validate the extension configuration.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->getExtension('vich_geographical')->validateConfiguration($container);
    }
}