<?php

namespace Bauhaus\Doubles\DiscoverNamespaceB;

use StdClass;

class ServiceWithScalarDependencies
{
    public function __construct(
        private StdClass $class,
        private bool $bool,
        private int $int,
        private string $string,
    ) {
    }
}
