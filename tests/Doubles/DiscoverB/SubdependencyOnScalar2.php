<?php

namespace Bauhaus\Doubles\DiscoverB;

class SubdependencyOnScalar2
{
    public function __construct(
        private SubdependencyOnScalar1 $x,
    ) {
    }
}
