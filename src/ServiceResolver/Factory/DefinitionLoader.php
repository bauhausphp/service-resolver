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
    /**
     * @throws DefinitionLoaderException
     * @return Definition[]
     */
    public function loadFromFiles(string ...$files): array
    {
        $this->assertFilesExist(...$files);
        $filesContent = $this->loadFiles(...$files);

        return $this->buildServiceDefinitions($filesContent);
    }

    private function assertFilesExist(string ...$files): void
    {
        $notFoundFiles = array_filter($files, fn ($f) => !file_exists($f));

        if ([] !== $notFoundFiles) {
            throw DefinitionLoaderException::filesNotFound(...$notFoundFiles);
        }
    }

    private function loadFiles(string ...$files): array
    {
        $invalidFiles = [];
        $allFilesContent = [];

        foreach ($files as $f) {
            $fileContent = require $f;

            if (false === is_array($fileContent)) {
                $invalidFiles[] = $f;
                continue;
            }

            $allFilesContent = array_merge($allFilesContent, $fileContent);
        }

        if ([] !== $invalidFiles) {
            throw DefinitionLoaderException::filesDoNotReturnArray(...$invalidFiles);
        }

        return $allFilesContent;
    }

    private function buildServiceDefinitions(array $filesContent): array
    {
        $invalidDefinitions = [];
        $validDefinitions = [];

        foreach ($filesContent as $k => $c) {
            try {
                $validDefinitions[$k] = ActualDefinition::create($c);
            } catch (DefinitionCouldNotBeCreated) {
                $invalidDefinitions[] = $k;
            }
        }

        if ([] !== $invalidDefinitions) {
            throw DefinitionLoaderException::invalidDefinitions(...$invalidDefinitions);
        }

        return $validDefinitions;
    }
}
