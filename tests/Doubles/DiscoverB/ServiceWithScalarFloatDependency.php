<?php

namespace Bauhaus\Doubles\DiscoverB;

class ServiceWithScalarFloatDependency
{
    public function __construct(
        private float $float,
    ) {
    }
}
