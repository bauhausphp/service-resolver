<?php

namespace Bauhaus\Doubles\DiscoverNamespaceA;

class CircularDependencyB
{
    public function __construct(
        private CircularDependencyA $a,
    ) {
    }
}
