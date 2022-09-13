[![Build Status]](https://github.com/bauhausphp/service-resolver/actions)
[![Coverage]](https://coveralls.io/github/bauhausphp/service-resolver?branch=main)

[![Stable Version]](https://packagist.org/packages/bauhaus/service-resolver)
[![Downloads]](https://packagist.org/packages/bauhaus/service-resolver)
[![PHP Version]](composer.json)
[![License]](LICENSE)

[Build Status]: https://img.shields.io/github/workflow/status/bauhausphp/service-resolver/CI?style=flat-square
[Coverage]: https://img.shields.io/coveralls/github/bauhausphp/service-resolver?style=flat-square
[Stable Version]: https://img.shields.io/packagist/v/bauhaus/service-resolver?style=flat-square
[Downloads]: https://img.shields.io/packagist/dt/bauhaus/service-resolver?style=flat-square
[PHP Version]: https://img.shields.io/packagist/php-v/bauhaus/service-resolver?style=flat-square
[License]: https://img.shields.io/github/license/bauhausphp/service-resolver?style=flat-square

> **Warning!** This package won't worry about backward compatibily for `v0.*`.

# Service Locator

Yet another service resolver (aka service container) implementation following
[PSR-11 Container interface](https://www.php-fig.org/psr/psr-11/).

## Installation

```sh
$ composer require bauhaus/service-resolver
```

## Using it

```php
<?php

use Bauhaus\ServiceResolver;
use Bauhaus\ServiceResolverSettings;

$settings = ServiceResolverSettings::new()
    ->withDefintionFiles(
        'path/file-1.php',
        'path/file-2.php',
    )
    ->withServices([
        'service-id-1' => fn () => YourService(), // lazy loaded
        'service-id-2' => new YourService(),
    ])
    ->withDiscoverableNamespaces(
        'App\\Namespace1',
        'App\\Namespace2',
    );

$serviceResolver = ServiceResolver::build($settings);

$serviceResolver->has($serviceId);
$serviceResolver->get($serviceId);
```

