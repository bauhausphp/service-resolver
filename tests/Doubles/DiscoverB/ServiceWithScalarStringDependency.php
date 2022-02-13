<?php

namespace Bauhaus\Doubles\DiscoverB;

class ServiceWithScalarStringDependency
{
    public function __construct(
        private string $string,
    ) {
    }
}
