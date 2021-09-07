<?php

namespace Bauhaus\ServiceResolver\Factory;

use Bauhaus\ServiceResolver\ActualDefinition;
use Bauhaus\ServiceResolver\Definition;
use Bauhaus\ServiceResolver\DefinitionCouldNotBeCreated;

/**
 * @internal
 */
final class DefinitionLoader
{
    private array $files;

    /**
     * @throws DefinitionLoaderException
     */
    public function __construct(string ...$files)
    {
        $this->files = $files;

        $this->assertFilesExist();
    }

    /**
     * @throws DefinitionLoaderException
     * @return Definition[]
     */
    public function createDefinitions(): array
    {
        $filesContent = $this->loadFiles();

        $definitions = [];
        $problematicIds = [];

        foreach ($filesContent as $id => $definition) {
            try {
                $definitions[$id] = ActualDefinition::create($definition);
            } catch (DefinitionCouldNotBeCreated) {
                $problematicIds[] = $id;
            }
        }

        if ([] !== $problematicIds) {
            throw DefinitionLoaderException::invalidDefinitions(...$problematicIds);
        }

        return $definitions;
    }

    private function assertFilesExist(): void
    {
        $notFoundFiles = array_filter($this->files, fn ($f) => !file_exists($f));

        if ([] !== $notFoundFiles) {
            throw DefinitionLoaderException::filesNotFound(...$notFoundFiles);
        }
    }

    private function loadFiles(): array
    {
        $allContent = [];
        $invalidFiles = [];

        foreach ($this->files as $f) {
            $content = require $f;

            if (false === is_array($content)) {
                $invalidFiles[] = $f;
                continue;
            }

            $allContent = array_merge($allContent, $content);
        }

        if ([] !== $invalidFiles) {
            throw DefinitionLoaderException::filesDoNotReturnArray(...$invalidFiles);
        }

        return $allContent;
    }
}
