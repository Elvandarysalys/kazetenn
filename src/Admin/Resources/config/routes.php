<?php

namespace Kazetenn\Admin\DependencyInjection\Loader\Configurator;

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->import('../../Controller/', 'annotation')
           ->prefix('%kazetenn_admin.admin_url%')
           ->namePrefix('kazetenn_admin_');
};
