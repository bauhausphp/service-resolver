<?php

namespace Bauhaus\Doubles\DiscoverNamespaceA;

class CircularDependencyC
{
    public function __construct(
        private CircularDependencyB $b,
    ) {
    }
}
