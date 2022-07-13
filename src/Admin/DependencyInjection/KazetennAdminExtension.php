<?php

namespace Kazetenn\Admin\DependencyInjection;

use Exception;
use Kazetenn\Admin\Model\AdminMenu;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class KazetennAdminExtension extends Extension
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $alias = $this->getAlias();

        foreach ($config as $key => $item) {
            if ($key === AdminMenu::PAGES_ENTRIES_NAME) {
                foreach ($item as $pageName => $pageData) {
                    $container->setParameter("$alias.pages.$pageName", $pageData);
                }
            }
            $container->setParameter($this->getAlias() . '.' . $key, $item);
        }

        $this->addAnnotatedClassesToCompile([
            '**\\Controller\\',
            '**\\Entity\\',
            'Kazetenn\\',
        ]);
    }
}
