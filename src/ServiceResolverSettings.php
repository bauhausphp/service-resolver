<?php

namespace Bauhaus;

final class ServiceResolverSettings
{
    private function __construct(
        public readonly array $services,
        public readonly array $definitionFiles,
        public readonly array $discoverableNamespaces,
    ) {
    }

    public static function new(): self
    {
        return new self([], [], []);
    }

    public function mergedWith(self $that): self
    {
        return new self(
            [...$this->services, ...$that->services],
            [...$this->definitionFiles, ...$that->definitionFiles],
            [...$this->discoverableNamespaces, ...$that->discoverableNamespaces],
        );
    }

    public function withServices(array $services): self
    {
        return new self($services, $this->definitionFiles, $this->discoverableNamespaces);
    }

    public function withDefinitionFiles(string ...$definitionFiles): self
    {
        return new self($this->services, $definitionFiles, $this->discoverableNamespaces);
    }

    public function withDiscoverableNamespaces(string ...$discoverableNamespaces): self
    {
        return new self($this->services, $this->definitionFiles, $discoverableNamespaces);
    }
}
