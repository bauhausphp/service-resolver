<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\ServiceDefinition;
use PHPUnit\Framework\TestCase;

class ServiceDefinitionContainerTest extends TestCase
{
    private ServiceDefinitionContainer $container;

    protected function setUp(): void
    {
        $this->container = new ServiceDefinitionContainer([
            'key' => ServiceDefinition::build(fn () => 'who cares?'),
        ]);
    }

    /**
     * @test
     */
    public function returnServiceDefinitionFromFileIfIdWasDefined(): void
    {
        $serviceDefinition = $this->container->get('key');

        $this->assertEquals(
            ServiceDefinition::build(fn () => 'who cares?'),
            $serviceDefinition,
        );
    }

    /**
     * @test
     */
    public function returnNullIfIdWasNotDefined(): void
    {
        $null = $this->container->get('another-key');

        $this->assertNull($null);
    }
}
