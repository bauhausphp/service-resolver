<?php

namespace Bauhaus;

final class ServiceResolverOptions
{
    private function __construct(
        private array $definitionFiles = [],
        private array $discoverableNamespaces = [],
    ) {
    }

    public static function new(): self
    {
        return new self([], []);
    }

    public function withDefinitionFiles(string ...$definitionFiles): self
    {
        return $this->cloneWith(definitionFiles: $definitionFiles);
    }

    public function withDiscoverableNamespaces(string ...$discoverableNamespaces): self
    {
        return $this->cloneWith(discoverableNamespaces: $discoverableNamespaces);
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

    private function cloneWith(
        ?array $definitionFiles = null,
        ?array $discoverableNamespaces = null,
    ): self {
        return new self(
            $definitionFiles ?? $this->definitionFiles,
            $discoverableNamespaces ?? $this->discoverableNamespaces,
        );
    }
}
