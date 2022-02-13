<?php

namespace Bauhaus\Doubles\DiscoverA;

use Bauhaus\Doubles\DiscoverB\DiscoverableB;
use Bauhaus\Doubles\NotDiscover\ServiceWithOneDependency;
use Bauhaus\Doubles\NotDiscover\ServiceWithoutDependencyA;

class ServiceWithManyDependencies
{
    public function __construct(
        private ServiceWithoutDependencyA $a,
        private ServiceWithOneDependency $b,
        private DiscoverableA1 $c,
        private DiscoverableA2 $d,
        private DiscoverableB $e,
    ) {
    }
}
