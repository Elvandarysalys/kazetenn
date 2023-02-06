<?php

namespace Kazetenn\Admin\DependencyInjection\Loader\Configurator;

use Kazetenn\Admin\Controller\LandingController;
use Kazetenn\Admin\Service\MenuHandler;
use Kazetenn\Admin\Service\PageHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
                             ->defaults()
                             ->autowire()
                             ->autoconfigure();

    $services->load('Kazetenn\\Admin\\', '../../*')
             ->exclude('../../{DependencyInjection, Tests}');

    $services->set(MenuHandler::class)
             ->arg('$adminPages', '%kazetenn_admin.pages%')
             ->arg('$authorizedRoles', '%kazetenn_admin.authorized_roles%')
             ->arg('$defaultTranslationDomain', '%kazetenn_admin.translation_domain%')
             ->arg('$menuEntries', '%kazetenn_admin.menu_entries%');

    $services->set(PageHandler::class)
             ->call('setContainer', [new Reference('service_container')]);
};
