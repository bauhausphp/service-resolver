<?php

namespace Bauhaus\Doubles\DiscoverNamespaceA;

class NotFoundMessage3
{
    public function __construct(
        private NotFoundMessage2 $x,
    ) {
    }
}
