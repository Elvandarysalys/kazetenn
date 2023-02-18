<?php
/**
 *
 *
 *
 *
 */

namespace Kazetenn\Users;

use Kazetenn\Users\Controller\SecurityController;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class KazetennUsers extends AbstractBundle
{
    public const ROLE_USER      = 'ROLE_USER';
    public const ROLE_REDACTION = 'ROLE_REDACTION';
    public const ROLE_ADMIN     = 'ROLE_ADMIN';

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
                   ->children()
                   ->booleanNode('hide_registration')
                   ->info('If true, the registration menu will be hidden but only if atl least one user has been registered.')
                   ->defaultTrue()
                   ->end()
                    ->scalarNode('redirect_to_route')
                    ->info('allow to set an auto redirect route on login.')
                    ->defaultNull()
                    ->end()
                   ->end();
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->prependExtensionConfig('webpack_encore', [
            'builds' => [
                'kazetenn_users' => '%kernel.project_dir%/public/bundles/kazetennusers'
            ]
        ]);

        $loader = new YamlFileLoader($builder, new FileLocator(dirname(__DIR__) . '/config'));
        $loader->load('security.yaml');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        foreach ($config as $key => $item) {
            $builder->setParameter($this->extensionAlias . '.' . $key, $item);
        }

        $services = $container->services()
                              ->defaults()
                              ->autowire()
                              ->autoconfigure();
//        ->bind('$hideRegistration', '%kazetenn_users.hide_registration%');

        $services->load('Kazetenn\\Users\\', './*')
                 ->exclude('./{DependencyInjection, Tests}');

        $services->set(SecurityController::class)
            ->arg('$hideRegistration', '%kazetenn_users.hide_registration%');
    }
}
