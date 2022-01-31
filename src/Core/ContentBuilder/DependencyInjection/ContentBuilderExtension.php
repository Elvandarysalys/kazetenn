<?php

namespace Kazetenn\Core\ContentBuilder\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use function dump;

class ContentBuilderExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $this->addAnnotatedClassesToCompile([
            '**\\Controller\\',
            '**\\Entity\\',
        ]);
    }

    public function prepend(ContainerBuilder $container)
    {
        // TODO: Implement prepend() method.
    }
}
