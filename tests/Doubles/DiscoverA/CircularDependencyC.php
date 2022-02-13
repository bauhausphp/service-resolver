<?php

namespace Bauhaus\Doubles\DiscoverA;

class CircularDependencyC
{
    public function __construct(
        private CircularDependencyB $b,
    ) {
    }
}
