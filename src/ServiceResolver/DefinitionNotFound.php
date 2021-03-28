<?php

namespace Bauhaus\ServiceResolver;

use RunTimeException;
use Psr\Container\NotFoundExceptionInterface as PsrNotFoundException;
use Throwable;

/**
 * @internal
 */
final class DefinitionNotFound extends RunTimeException implements PsrNotFoundException
{
    /** @var string[] */
    private array $requestedIdStack;

    private function __construct(?Throwable $originalReason, string ...$requestedIdStack)
    {
        parent::__construct(previous: $originalReason);

        $this->requestedIdStack = $requestedIdStack;
        $this->buildMessage();
    }

    public static function with(string $requestedId, Throwable $original = null): self
    {
        return new self($original, $requestedId);
    }

    public static function fromPreviousNotFound(string $requestedId, self $previous): self
    {
        return new self(
            $previous->getPrevious(),
            $requestedId,
            ...$previous->requestedIdStack,
        );
    }

    private function buildMessage(): void
    {
        $this->message = <<<MSG
            Definition not found
            {$this->buildTrace()}
            MSG;
    }

    private function buildTrace(): string
    {
        $indent = '    ';

        $traceMessages = [];
        foreach ($this->requestedIdStack as $k => $id) {
            $traceMessages[] = "$indent{$this->messageByStackKey($k)} -> $id";
        }

        $traceMessageGlue = "\n$indent V\n";
        $trace = implode($traceMessageGlue, $traceMessages);

        return <<<TRACE
            $trace
            $indent > {$this->notResolvedError()}
            TRACE;
    }

    private function messageByStackKey(int $key): string
    {
        $firstKey = 0;
        $lastKey = count($this->requestedIdStack) - 1;

        return match ($key) {
            $firstKey => "requested",
            $lastKey => "dependency not resolved",
            default => "dependency resolved",
        };
    }

    private function notResolvedError(): string
    {
        return match ($this->getPrevious()) {
            null => 'Not found',
            default => $this->getPrevious()->getMessage(),
        };
    }
}
