<?php

namespace Bauhaus\Doubles\DiscoverA;

class CircularDependencyD
{
    public function __construct(
        private CircularDependencyC $c,
    ) {
    }
}
