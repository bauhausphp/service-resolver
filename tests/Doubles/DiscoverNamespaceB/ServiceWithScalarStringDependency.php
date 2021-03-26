<?php

namespace Bauhaus\Doubles\DiscoverNamespaceB;

class ServiceWithScalarStringDependency
{
    public function __construct(
        private string $string,
    ) {
    }
}
