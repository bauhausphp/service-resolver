<?php

namespace Bauhaus\Doubles\DiscoverNamespaceA;

class NotFoundMessage2
{
    public function __construct(
        private NotFoundMessage1 $x,
    ) {
    }
}
