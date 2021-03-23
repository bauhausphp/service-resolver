<?php

namespace Bauhaus;

use Bauhaus\Doubles\ServiceWithOneDependency;
use Bauhaus\Doubles\ServiceWithoutDependency;
use Bauhaus\ServiceResolver\Resolvers\CircularDependencyDetector;
use Bauhaus\ServiceResolver\Resolvers\Discoverer;
use Bauhaus\ServiceResolver\Resolvers\MemoryCache;
use Bauhaus\ServiceResolver\Resolvers\ServiceDefinitionContainer;
use Bauhaus\ServiceResolver\ServiceDefinition;
use PHPUnit\Framework\TestCase;

class ServiceResolverFactoryTest extends TestCase
{
    public function filesWithExpectedServiceDefinitions(): array
    {
        return [
            'one file' => [
                [
                    __DIR__ . '/ServiceResolver/definitions-file-1.php',
                ],
                [
                    ServiceWithoutDependency::class => ServiceDefinition::build(fn () => 'who cares?'),
                ],
            ],
            'two files' => [
                [
                    __DIR__ . '/ServiceResolver/definitions-file-1.php',
                    __DIR__ . '/ServiceResolver/definitions-file-2.php',
                ],
                [
                    ServiceWithoutDependency::class => ServiceDefinition::build(fn () => 'who cares?'),
                    ServiceWithOneDependency::class => ServiceDefinition::build(fn () => 'who cares?'),
                    'service-alias' => ServiceDefinition::build(fn () => 'who cares?'),
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider filesWithExpectedServiceDefinitions
     */
    public function buildResolverFromProvidedFiles(
        array $files,
        array $expectedServiceDefinitions,
    ): void {
        $options = ServiceResolverOptions::create()
            ->withDefinitionFiles(...$files);

        $resolver = ServiceResolverFactory::build($options);

        $expected = new ServiceResolver(
            new MemoryCache(
                new CircularDependencyDetector(
                    new ServiceDefinitionContainer($expectedServiceDefinitions),
                ),
            ),
        );
        $this->assertEquals($expected, $resolver);
    }

    /**
     * @test
     * @dataProvider filesWithExpectedServiceDefinitions
     */
    public function buildResolverWithDiscovererIfNamespaceWasProvided(): void
    {
        $options = ServiceResolverOptions::create()
            ->withDiscoverableNamespaces('Some\\Namespace\\');

        $resolver = ServiceResolverFactory::build($options);

        $expected = new ServiceResolver(
            new MemoryCache(
                new CircularDependencyDetector(
                    new Discoverer(
                        new ServiceDefinitionContainer([]),
                        'Some\\Namespace\\',
                    ),
                ),
            ),
        );
        $this->assertEquals($expected, $resolver);
    }
}
