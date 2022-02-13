<?php

namespace Bauhaus\Doubles\DiscoverA;

use Exception;

class ServiceThatThrowsException
{
    public function __construct()
    {
        throw new Exception('Error occurred');
    }
}
