<?php

namespace Bauhaus\Doubles\DiscoverNamespaceB;

use StdClass;

class ServiceWithVariadicDependency
{
    public function __construct(StdClass ...$classes)
    {
    }
}
