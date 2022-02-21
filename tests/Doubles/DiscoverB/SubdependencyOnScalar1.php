<?php

namespace Bauhaus\Doubles\DiscoverB;

class SubdependencyOnScalar1
{
    public function __construct(
        private ServiceWithScalarIntDependency $x,
    ) {
    }
}
