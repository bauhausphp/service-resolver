<?php

namespace Bauhaus\ServiceResolver;

use LogicException;
use Psr\Container\ContainerExceptionInterface as PsrContainerException;
use Throwable;

/**
 * @internal
 */
final class DefinitionLoadingError extends LogicException implements PsrContainerException
{
    private function __construct(
        private readonly Throwable $originalError,
        private readonly string $requestedId,
        /** @var string[] */ private readonly array $dependencyIdStack,
    ) {
        parent::__construct(
            message: $this->buildMessage(),
            previous: $originalError,
        );
    }

    public static function becauseOf(Throwable $originalError, string $problematicServiceId): self
    {
        return new self($originalError, $problematicServiceId, []);
    }

    public static function trace(self $previous, string $currentServiceId): self
    {
        return new self(
            $previous->originalError,
            $currentServiceId,
            [$previous->requestedId, ...$previous->dependencyIdStack],
        );
    }

    private function buildMessage(): string
    {
        return <<<MSG
            Definition evaluation error
            {$this->buildTrace()}
            MSG;
    }

    private function buildTrace(): string
    {
        $messages = ["    requested id -> {$this->requestedId}"];

        foreach ($this->dependencyIdStack as $dependencyId) {
            $messages[] = "    dependency id -> $dependencyId";
        }

        $glue = "\n     V\n";
        $trace = implode($glue, $messages);

        return <<<TRACE
            $trace
                 > {$this->originalError->getMessage()}
            TRACE;
    }
}
