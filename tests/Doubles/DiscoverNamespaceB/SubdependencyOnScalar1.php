<?php

namespace Bauhaus\Doubles\DiscoverNamespaceB;

class SubdependencyOnScalar1
{
    public function __construct(
        private ServiceWithScalarIntDependency $x,
    ) {
    }
}
