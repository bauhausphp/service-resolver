<?php

use Bauhaus\Doubles\NotDiscover\ServiceWithoutDependencyA;
use Bauhaus\Doubles\NotDiscover\CallableService;

return [
    'service-alias' => ServiceWithoutDependencyA::class,
    'without-callback' => new StdClass(),
    'concrete-callable-object' => new CallableService(),
];
