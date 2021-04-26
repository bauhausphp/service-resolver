<?php

namespace Bauhaus;

final class ServiceResolverOptions
{
    private array $definitionFiles = [];
    private array $discoverableNamespaces = [];

    private function __construct()
    {
    }

    public static function empty(): self
    {
        return new self();
    }

    public function withDefinitionFiles(string ...$files): self
    {
        $options = $this->clone();
        $options->definitionFiles = $files;

        return $options;
    }

    public function withDiscoverableNamespaces(string ...$namespaces): self
    {
        $options = $this->clone();
        $options->discoverableNamespaces = $namespaces;

        return $options;
    }

    /**
     * @return string[]
     */
    public function definitionFiles(): array
    {
        return $this->definitionFiles;
    }

    public function isDiscoverableEnabled(): bool
    {
        return [] !== $this->discoverableNamespaces;
    }

    /**
     * @return string[]
     */
    public function discoverableNamespaces(): array
    {
        return $this->discoverableNamespaces;
    }

    private function clone(): self
    {
        return clone $this;
    }
}
