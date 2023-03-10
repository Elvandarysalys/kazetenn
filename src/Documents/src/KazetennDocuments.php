<?php

namespace Kazetenn\Documents;

use Kazetenn\Admin\Model\AdminMenu;
use Kazetenn\Users\KazetennUsers;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;
use function Symfony\Component\Translation\t;

class KazetennDocuments extends AbstractBundle
{
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // activating the timestampable and blameable extension
        $builder->prependExtensionConfig('stof_doctrine_extensions', [
            'orm' => [
                'default' => [
                    'timestampable' => true,
                    'blameable'     => true
                ]
            ]
        ]);

        $builder->prependExtensionConfig('webpack_encore', [
            'builds' => [
                'kazetenn_core' => '%kernel.project_dir%/public/bundles/kazetenndocuments'
            ]
        ]);

        if ($container->env() === 'dev'){
            $builder->prependExtensionConfig('maker', [
                'root_namespace' => 'Kazetenn\Documents'
            ]);
        }
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $services = $container->services()
                              ->defaults()
                              ->autowire()
                              ->autoconfigure();

        $services->load('Kazetenn\\Documents\\', './*')
                 ->exclude('./{DependencyInjection, Tests}');


//        $services->set('Kazetenn\Core\Service\ContentService')
//                 ->arg('$availableContentTypes', tagged_iterator('kazetenn.content_type_tag'));
//        $services->set('Kazetenn\Core\Service\BlockService')
//                 ->arg('$availableBlockTypes', tagged_iterator('kazetenn.block_type_tag'));
    }
}
