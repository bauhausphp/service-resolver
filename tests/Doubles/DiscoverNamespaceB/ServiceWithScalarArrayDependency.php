<?php

namespace Bauhaus\Doubles\DiscoverNamespaceB;

class ServiceWithScalarArrayDependency
{
    public function __construct(
        private array $arr,
    ) {
    }
}
