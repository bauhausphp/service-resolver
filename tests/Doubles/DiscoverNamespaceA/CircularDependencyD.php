<?php

namespace Bauhaus\Doubles\DiscoverNamespaceA;

class CircularDependencyD
{
    public function __construct(
        private CircularDependencyC $a,
    ) {
    }
}
