<?php

namespace Bauhaus\Doubles\DiscoverNamespaceB;

class SubdependencyOnScalar2
{
    public function __construct(
        private SubdependencyOnScalar1 $x,
    ) {
    }
}
