<?php

namespace Bauhaus\ServiceResolver;

use InvalidArgumentException;

/**
 * @internal
 */
final class ServiceDefinitionLoaderException extends InvalidArgumentException
{
    private function __construct(string $message, string ...$invalidThings)
    {
        $invalidThings = implode(', ', $invalidThings);

        parent::__construct("$message: $invalidThings");
    }

    public static function filesNotFound(string ...$files): self
    {
        return new self('Files not found', ...$files);
    }

    public static function filesDoNotReturnArray(string ...$files): self
    {
        return new self('Files must return array', ...$files);
    }

    public static function invalidServiceDefinition(string ...$ids): self
    {
        return new self('Invalid service definitions', ...$ids);
    }
}
