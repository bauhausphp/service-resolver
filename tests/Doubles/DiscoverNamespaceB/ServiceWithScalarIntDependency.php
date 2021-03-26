<?php

namespace Bauhaus\Doubles\DiscoverNamespaceB;

class ServiceWithScalarIntDependency
{
    public function __construct(
        private int $int,
    ) {
    }
}
