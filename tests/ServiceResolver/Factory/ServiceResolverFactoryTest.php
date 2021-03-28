<?php

namespace Bauhaus;

use Bauhaus\Doubles\ServiceWithOneDependency;
use Bauhaus\Doubles\ServiceWithoutDependency;
use Bauhaus\ServiceResolver\ActualDefinition;
use Bauhaus\ServiceResolver\Factory\ServiceResolverFactory;
use Bauhaus\ServiceResolver\Resolvers\CircularDependencyDetector\CircularDependencyDetector;
use Bauhaus\ServiceResolver\Resolvers\Discoverer\Discoverer;
use Bauhaus\ServiceResolver\Resolvers\MemoryCache\MemoryCache;
use Bauhaus\ServiceResolver\Resolvers\DefinitionCollection;
use PHPUnit\Framework\TestCase;

class ServiceResolverFactoryTest extends TestCase
{
    use DoubleDefinitionTrait;

    public function filesWithExpectedServiceDefinitions(): array
    {
        return [
            'one file' => [
                [
                   $this->definitionPath('definitions-file-1.php'),
                ],
                [
                    ServiceWithoutDependency::class => ActualDefinition::create(fn () => 'who cares?'),
                    'without-callback' => ActualDefinition::create(fn () => 'who cares?'),
                ],
            ],
            'two files' => [
                [
                    $this->definitionPath('definitions-file-1.php'),
                    $this->definitionPath('definitions-file-2.php'),
                ],
                [
                    ServiceWithoutDependency::class => ActualDefinition::create(fn () => 'who cares?'),
                    ServiceWithOneDependency::class => ActualDefinition::create(fn () => 'who cares?'),
                    'service-alias' => ActualDefinition::create(fn () => 'who cares?'),
                    'without-callback' => ActualDefinition::create(fn () => 'who cares?'),
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
        $options = ServiceResolverOptions::empty()
            ->withDefinitionFiles(...$files);

        $resolver = ServiceResolverFactory::build($options);

        $expected = new ServiceResolver(
            new MemoryCache(
                new CircularDependencyDetector(
                    new DefinitionCollection($expectedServiceDefinitions),
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
        $options = ServiceResolverOptions::empty()
            ->withDiscoverableNamespaces(
                'Some\\Namespace\\',
                '\\SomeOther',
            );

        $resolver = ServiceResolverFactory::build($options);

        $expected = new ServiceResolver(
            new MemoryCache(
                new CircularDependencyDetector(
                    new Discoverer(
                        new DefinitionCollection([]),
                        'Some\\Namespace\\',
                        '\\SomeOther',
                    ),
                ),
            ),
        );
        $this->assertEquals($expected, $resolver);
    }
}
