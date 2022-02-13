<?php

namespace Bauhaus\Doubles\DiscoverA;

class DependencyOnNotFoundService2
{
    public function __construct(
        private DependencyOnNotFoundService1 $x,
    ) {
    }
}
