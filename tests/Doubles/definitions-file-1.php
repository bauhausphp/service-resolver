<?php

use Bauhaus\Doubles\NotDiscover\ServiceWithOneDependency;
use Bauhaus\Doubles\NotDiscover\ServiceWithoutDependencyA;
use Psr\Container\ContainerInterface as PsrContainer;

return [
    ServiceWithOneDependency::class => fn (PsrContainer $c) => new ServiceWithOneDependency($c->get(ServiceWithoutDependencyA::class)),
    'callable-with-dependency' => fn (ServiceWithoutDependencyA $d) => new ServiceWithOneDependency($d),
    ServiceWithoutDependencyA::class => fn () => new ServiceWithoutDependencyA(),
];
