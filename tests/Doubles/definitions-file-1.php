<?php

use Bauhaus\Doubles\ServiceWithoutDependency;

return [
    ServiceWithoutDependency::class => fn () => new ServiceWithoutDependency(),
    'without-callback' => new StdClass(),
];
