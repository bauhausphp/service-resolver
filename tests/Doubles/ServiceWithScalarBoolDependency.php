<?php

namespace Bauhaus\Doubles;

class ServiceWithScalarBoolDependency
{
    public function __construct(
        private bool $bool,
    ) {
    }
}
