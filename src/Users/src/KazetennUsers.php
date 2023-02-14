<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Users;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class KazetennUsers extends AbstractBundle
{
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $loader = new YamlFileLoader($builder, new FileLocator(dirname(__DIR__) . '/config'));
        $loader->load('security.yaml');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $services = $container->services()
                              ->defaults()
                              ->autowire()
                              ->autoconfigure();

        $services->load('Kazetenn\\Users\\', './*')
                 ->exclude('./{DependencyInjection, Tests}');
    }
}
