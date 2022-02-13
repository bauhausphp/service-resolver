<?php

namespace Bauhaus\ServiceResolver\Container;

use Bauhaus\ServiceResolver\Identifier;
use Bauhaus\ServiceResolver\Locator;
use Bauhaus\ServiceResolverOptions;

/**
 * @internal
 */
final class ProvidedServiceContainer implements Locator
{
    private function __construct(
        /** @var ProvidedService[] */ private readonly array $services,
    ) {
    }

    public static function build(ServiceResolverOptions $options): self
    {
        $files = array_map(fn (string $f): DefinitionFile => new DefinitionFile($f), $options->definitionFiles);
        $providedServices = array_reduce($files, fn (array $s, DefinitionFile $f): array => array_merge($s, $f->load()), $options->services);

        return new self(array_map(fn (mixed $s): ProvidedService => ProvidedService::create($s), $providedServices));
    }

    public function find(Identifier $id): ?ProvidedService
    {
        return $this->services[(string) $id] ?? null;
    }
}
