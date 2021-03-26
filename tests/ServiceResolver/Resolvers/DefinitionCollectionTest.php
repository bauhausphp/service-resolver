<?php

namespace Bauhaus\ServiceResolver\Resolvers;

use Bauhaus\ServiceResolver\Definition;
use PHPUnit\Framework\TestCase;

class DefinitionCollectionTest extends TestCase
{
    private DefinitionCollection $container;

    protected function setUp(): void
    {
        $this->container = new DefinitionCollection([
            'key' => $this->createMock(Definition::class),
        ]);
    }

    /**
     * @test
     */
    public function returnServiceDefinitionFromFileIfIdWasDefined(): void
    {
        $definition = $this->container->get('key');

        $this->assertEquals($this->createMock(Definition::class), $definition);
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
