<?php

namespace Bauhaus\Doubles;

class ServiceWithScalarArrayDependency
{
    public function __construct(
        private array $arr,
    ) {
    }
}
