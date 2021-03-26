<?php

namespace Bauhaus\Doubles\DiscoverNamespaceA;

use Bauhaus\Doubles\DiscoverNamespaceB\DiscoverableB;
use Bauhaus\Doubles\ServiceWithOneDependency;
use Bauhaus\Doubles\ServiceWithoutDependency;

class ServiceWithManyDependency
{
    public function __construct(
        private ServiceWithoutDependency $a,
        private ServiceWithOneDependency $b,
        private DiscoverableA1 $c,
        private DiscoverableA2 $d,
        private DiscoverableB $e,
    ) {
    }
}
