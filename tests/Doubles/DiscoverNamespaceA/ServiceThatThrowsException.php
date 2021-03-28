<?php

namespace Bauhaus\Doubles\DiscoverNamespaceA;

use Exception;

class ServiceThatThrowsException
{
    public function __construct()
    {
        throw new Exception('Error occurred');
    }
}
