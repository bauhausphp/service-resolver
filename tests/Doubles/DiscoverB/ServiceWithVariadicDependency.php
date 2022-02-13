<?php

namespace Bauhaus\Doubles\DiscoverB;

use StdClass;

class ServiceWithVariadicDependency
{
    public function __construct(StdClass ...$classes)
    {
    }
}
