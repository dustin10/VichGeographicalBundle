<?php

$vendorDir = __DIR__.'/../../../..';
require_once $vendorDir.'/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'                => array($vendorDir.'/symfony/src', $vendorDir.'/bundles'),
    'Vich'                   => $vendorDir.'/bundles',
    'Doctrine\\Common'       => $vendorDir.'/doctrine-common/lib',
    'Doctrine\\DBAL'         => $vendorDir.'/doctrine-dbal/lib',
    'Doctrine'               => $vendorDir.'/doctrine/lib',
));
$loader->register();