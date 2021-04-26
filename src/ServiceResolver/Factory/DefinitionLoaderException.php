<?php

namespace Bauhaus\ServiceResolver\Factory;

use InvalidArgumentException;

/**
 * @internal
 */
final class DefinitionLoaderException extends InvalidArgumentException
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

    public static function invalidDefinitions(string ...$ids): self
    {
        return new self('Invalid definitions', ...$ids);
    }
}
