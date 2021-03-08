<?php

declare(strict_types=1);

/**
 * Primary service for populating the plugins placeholders.
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\FileIO;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;

class FilePopulator
{
    protected Finder $finder;
    protected Filesystem $fileSystem;

    public function __construct(
        Finder $finder,
        Filesystem $fileSystem
    ) {
        $this->finder = $finder;
        $this->fileSystem = $fileSystem;
    }

    /**
     * Translates all files in passed directory.
     *
     * @param string $directory
     * @param array<string, string> $translations
     * @param array<string> $excludedDirectories
     * @param array<string> $excludedFiles
     * @return void
     */
    public function translateFiles(
        string $directory,
        array $translations,
        array $excludedDirectories = [],
        array $excludedFiles = []
    ): void {
        
        $files = $this->finder->in($directory)
        ->notPath($excludedFiles)
        ->exclude($excludedDirectories)
        ->filter(fn($file) => ! $file->isDir() && $file->isWritable());
        
        foreach ($files as $file) {
            $this->populateFile(
                $file,
                $translations
            );
        }
    }

    /**
     * Finds a file based on its filename and populates the values.
     *
     * @param string $filename
     * @param array<string, string> $translations
     * @return bool
     */
    public function findAndPopulateFile(
        string $filename,
        array $translations,
        string $directory
    ): bool {
        if ($this->fileSystem->exists($directory . DIRECTORY_SEPARATOR . $filename)) {
            $file = new SplFileInfo($directory . DIRECTORY_SEPARATOR . $filename, '', '');
            return $this->populateFile($file, $translations);
        }
        return false;
    }

    /**
     * Popultes a single file from a passed array of translations.
     *
     * @param string $path
     * @param array<string, string> $translations
     * @return bool
     */
    public function populateFile(SplFileInfo $file, array $translations): bool
    {
        $contents = $file->getContents();
        $translatedContents = \strtr($contents, $translations);
        return (bool) \file_put_contents($file->getRealPath(), $translatedContents);
    }
}
