<?php

namespace Bauhaus\Doubles;

class ServiceWithVariadicDependency
{
    public function __construct(\StdClass ...$classes)
    {
    }
}
