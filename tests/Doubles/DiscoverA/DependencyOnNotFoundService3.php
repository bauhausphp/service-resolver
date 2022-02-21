<?php

namespace Bauhaus\Doubles\DiscoverA;

class DependencyOnNotFoundService3
{
    public function __construct(
        private DependencyOnNotFoundService2 $x,
    ) {
    }
}
