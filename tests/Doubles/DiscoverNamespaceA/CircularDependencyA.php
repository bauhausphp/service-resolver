<?php

namespace Bauhaus\Doubles\DiscoverNamespaceA;

class CircularDependencyA
{
    public function __construct(
        private CircularDependencyB $b,
    ) {
    }
}
