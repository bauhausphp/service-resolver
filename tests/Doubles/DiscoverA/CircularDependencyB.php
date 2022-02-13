<?php

namespace Bauhaus\Doubles\DiscoverA;

class CircularDependencyB
{
    public function __construct(
        private CircularDependencyA $a,
    ) {
    }
}
