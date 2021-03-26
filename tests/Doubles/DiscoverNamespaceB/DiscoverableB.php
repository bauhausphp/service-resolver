<?php

namespace Bauhaus\Doubles\DiscoverNamespaceB;

use Bauhaus\Doubles\DiscoverNamespaceA\DiscoverableA1;
use Bauhaus\Doubles\DiscoverNamespaceA\DiscoverableA2;

class DiscoverableB
{
    public function __construct(
        private DiscoverableA1 $a,
        private DiscoverableA2 $b,
    ) {
    }
}
