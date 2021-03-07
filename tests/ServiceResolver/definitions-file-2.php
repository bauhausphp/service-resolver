<?php

use Bauhaus\Doubles\ServiceWithOneDependency;
use Bauhaus\Doubles\ServiceWithoutDependency;
use Psr\Container\ContainerInterface as PsrContainer;

return [
    ServiceWithOneDependency::class =>
        fn (PsrContainer $c) => new ServiceWithOneDependency(
            $c->get(ServiceWithoutDependency::class),
        ),
    'service-alias' => ServiceWithoutDependency::class,
];
