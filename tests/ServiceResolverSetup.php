<?php

namespace Bauhaus;

use Bauhaus\Doubles\DiscoverA\DiscoverableA1;
use Bauhaus\Doubles\NotDiscover\ServiceWithoutDependencyA;
use Bauhaus\Doubles\NotDiscover\ServiceWithoutDependencyB;

trait ServiceResolverSetup
{
    protected readonly ServiceResolver $resolver;
    private readonly ServiceResolverSettings $settings;

    /**
     * @before
     */
    public function setUpServiceResolver(): void
    {
        $this->resolver = ServiceResolver::build($this->settings);
    }

    /**
     * @before
     */
    public function setUpSettings(): void
    {
        $this->settings = ServiceResolverSettings::new()
            ->withServices([
                'callable' => fn () => new ServiceWithoutDependencyA(),
                'concrete-object' => new ServiceWithoutDependencyB(),
                'alias-to-callable' => 'callable',
                'alias-to-concrete-object' => 'concrete-object',
                'alias-to-discoverable' => DiscoverableA1::class,
                'alias-to-non-existing-id' => 'non-existing-id',
            ])
            ->withDefinitionFiles(
                __DIR__ . '/Doubles/definitions-file-1.php',
                __DIR__ . '/Doubles/definitions-file-2.php',
            )
            ->withDiscoverableNamespaces(
                'Bauhaus\\Doubles\\DiscoverA',
                'Bauhaus\\Doubles\\DiscoverB',
            );
    }
}