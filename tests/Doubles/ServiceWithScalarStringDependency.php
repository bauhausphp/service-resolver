<?php

namespace Bauhaus\Doubles;

class ServiceWithScalarStringDependency
{
    public function __construct(
        private string $string,
    ) {
    }
}
