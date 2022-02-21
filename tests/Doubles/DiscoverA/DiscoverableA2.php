<?php

namespace Bauhaus\Doubles\DiscoverA;

class DiscoverableA2
{
    public function __construct(
        private DiscoverableA1 $a,
    ) {
    }
}
