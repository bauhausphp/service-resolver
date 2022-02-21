<?php

namespace Bauhaus\Doubles\DiscoverB;

use Bauhaus\Doubles\DiscoverA\DiscoverableA1;
use Bauhaus\Doubles\DiscoverA\DiscoverableA2;

class DiscoverableB
{
    public function __construct(
        private DiscoverableA1 $a,
        private DiscoverableA2 $b,
    ) {
    }
}
