<?php

namespace Bauhaus\ServiceResolver\Discoverer\DependencyLoader;

use LogicException;
use Psr\Container\ContainerInterface as PsrContainer;
use Psr\Container\NotFoundExceptionInterface as PsrNotFoundException;
use ReflectionParameter as RParam;

/**
 * @internal
 */
final class LoadableDependency
{
    private const PRIMITIVE_TYPES = ['bool', 'int', 'float', 'string', 'array'];

    public function __construct(
        private readonly RParam $rParam
    ) {
        $this->assertIsLoadable();
    }

    public function load(PsrContainer $psrContainer): object
    {
        try {
            return $psrContainer->get($this->type());
        } catch (PsrNotFoundException) {
            throw $this->logicException('Cannot find dependency');
        }
    }

    private function type(): string
    {
        return $this->rParam->getType()->getName();
    }

    private function hasType(): bool
    {
        return $this->rParam->hasType();
    }

    private function isVariadic(): bool
    {
        return $this->rParam->isVariadic();
    }

    private function isPrimitiveType(): bool
    {
        return in_array($this->rParam->getType(), self::PRIMITIVE_TYPES);
    }

    private function assertIsLoadable(): void
    {
        if (!$this->hasType()) {
            throw $this->logicException('Cannot load dependency without type');
        }

        if ($this->isPrimitiveType()) {
            throw $this->logicException('Cannot load primitive type dependency');
        }

        if ($this->isVariadic()) {
            throw $this->logicException('Cannot load variadic dependency');
        }
    }

    private function logicException(string $msg): LogicException
    {
        return new LogicException("$msg: {$this->formattedForException()}");
    }

    private function formattedForException(): string
    {
        $str = "\${$this->rParam->getName()}";
        $str = $this->isVariadic() ? "...$str" : $str;
        $str = $this->hasType() ? "{$this->type()} $str" : $str;

        return $str;
    }
}
