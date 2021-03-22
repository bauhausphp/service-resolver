<?php

namespace Bauhaus\Doubles;

class ServiceWithOneDependency
{
    public function __construct(
        private ServiceWithoutDependency $dep,
    ) {
    }
}
