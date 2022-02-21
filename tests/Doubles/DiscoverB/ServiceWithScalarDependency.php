<?php

namespace Bauhaus\Doubles\DiscoverB;

class ServiceWithScalarDependency
{
    public function __construct(
        private int $integer,
    ) {
    }
}
