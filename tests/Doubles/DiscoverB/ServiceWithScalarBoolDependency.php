<?php

namespace Bauhaus\Doubles\DiscoverB;

class ServiceWithScalarBoolDependency
{
    public function __construct(
        private bool $bool,
    ) {
    }
}
