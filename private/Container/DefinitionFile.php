<?php

namespace Bauhaus\ServiceResolver\Container;

use InvalidArgumentException;

/**
 * @internal
 */
final class DefinitionFile
{
    public function __construct(
        private readonly string $path
    ) {
        $this->assertFileExist();
    }

    public function load(): array
    {
        $content = require $this->path;

        return is_array($content) ? $content : throw $this->invalidArgException('Definition file must return array');
    }

    private function assertFileExist(): void
    {
        if (!file_exists($this->path)) {
            throw $this->invalidArgException('Definition file does not exist');
        }
    }

    private function invalidArgException(string $msg): InvalidArgumentException
    {
        return new InvalidArgumentException("$msg: {$this->path}");
    }
}
