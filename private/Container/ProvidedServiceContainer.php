<?php

namespace Bauhaus\ServiceResolver\Container;

use Bauhaus\ServiceResolver\Identifier;
use Bauhaus\ServiceResolver\Locator;
use Bauhaus\ServiceResolverSettings;

/**
 * @internal
 */
final readonly class ProvidedServiceContainer implements Locator
{
    private function __construct(
        /** @var ProvidedService[] */ private array $services,
    ) {
    }

    public static function build(ServiceResolverSettings $settings): self
    {
        $files = array_map(
            fn (string $f): DefinitionFile => new DefinitionFile($f),
            $settings->definitionFiles,
        );

        $providedServices = array_reduce(
            $files,
            fn (array $s, DefinitionFile $f): array => array_merge($s, $f->load()),
            $settings->services,
        );

        return new self(array_map(
            fn (mixed $s): ProvidedService => ProvidedService::create($s),
            $providedServices,
        ));
    }

    public function find(Identifier $id): ?ProvidedService
    {
        return $this->services[(string) $id] ?? null;
    }
}
