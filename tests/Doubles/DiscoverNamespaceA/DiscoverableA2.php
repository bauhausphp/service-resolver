<?php

namespace Bauhaus\Doubles\DiscoverNamespaceA;

class DiscoverableA2
{
    public function __construct(
        private DiscoverableA1 $a,
    ) {
    }
}
