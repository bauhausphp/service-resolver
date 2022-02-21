<?php

namespace Bauhaus\Doubles\DiscoverB;

class ServiceWithScalarArrayDependency
{
    public function __construct(
        private array $arr,
    ) {
    }
}
