<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Definition;
use PHPUnit\Framework\TestCase;

class DefinitionCollectionTest extends TestCase
{
    private DefinitionCollection $container;
    private Definition $definition;

    protected function setUp(): void
    {
        $this->definition = $this->createMock(Definition::class);
        $this->container = new DefinitionCollection([
            'key' => $this->definition,
        ]);
    }

    /**
     * @test
     */
    public function returnServiceDefinitionFromFileIfIdWasDefined(): void
    {
        $definition = $this->container->get('key');

        $this->assertSame($this->definition, $definition);
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
