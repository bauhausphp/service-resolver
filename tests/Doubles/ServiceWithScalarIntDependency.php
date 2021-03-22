<?php

namespace Bauhaus\Doubles;

class ServiceWithScalarIntDependency
{
    public function __construct(
        private int $int,
    ) {
    }
}
