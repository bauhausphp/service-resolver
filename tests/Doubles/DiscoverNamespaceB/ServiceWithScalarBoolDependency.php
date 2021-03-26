<?php

namespace Bauhaus\Doubles\DiscoverNamespaceB;

class ServiceWithScalarBoolDependency
{
    public function __construct(
        private bool $bool,
    ) {
    }
}
