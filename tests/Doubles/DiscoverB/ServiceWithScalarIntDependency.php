<?php

namespace Bauhaus\Doubles\DiscoverB;

class ServiceWithScalarIntDependency
{
    public function __construct(
        private int $int,
    ) {
    }
}
