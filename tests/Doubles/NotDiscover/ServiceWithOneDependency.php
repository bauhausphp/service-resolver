<?php

namespace Bauhaus\Doubles\NotDiscover;

class ServiceWithOneDependency
{
    public function __construct(
        private ServiceWithoutDependencyA $dep,
    ) {
    }
}
