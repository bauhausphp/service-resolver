<?php

namespace Bauhaus\Doubles\DiscoverA;

class CircularDependencyA
{
    public function __construct(
        private CircularDependencyB $b,
    ) {
    }
}
