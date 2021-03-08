<?php

/**
 * Handles all Repository operations
 *
 * @package PinkCrab Plugin Builder\Git
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @since 0.2.0
 */

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Git;

use Exception;
use Cz\Git\GitRepository;
use Symfony\Component\Filesystem\Filesystem;

class Repository
{
    
    protected Filesystem $fileSystem;

    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * Clones the repo and checks out to defined branch.
     *
     * @since 0.2.0
     * @param string $sourceRepo
     * @param string $desination
     * @param string $branch='master'
     * @return self
     */
    public function clone(
        string $sourceRepo,
        string $desination,
        string $branch = 'master'
    ): self {
        $this->rmDirOrFail($desination);
        $repo = GitRepository::cloneRepository($sourceRepo, $desination);
        $repo->checkout($branch);

        return $this;
    }

    /**
     * Attempts to remove a directory, throws and exception if fails.
     * Will skip if directory doesnt exist.
     *
     * @since 0.2.0
     * @throws Exception
     * @return void
     */
    public function rmDirOrFail(string $path): void
    {
        if ($this->fileSystem->exists($path)) {
            $this->fileSystem->remove($path);

            // Ensure removed, throw if not.
            if ($this->fileSystem->exists($path)) {
                throw new Exception("Failed to remove {$path} directory.");
            }
        }
    }

    /**
     * Removes the .git file based on the dir.
     *
     * @since 0.2.0
     * @param string $gitConfig
     * @return self
     */
    public function removeGitConfig(string $gitConfig): self
    {
        $gitConfig = str_ends_with($gitConfig, '/.git')
            ? $gitConfig
            : rtrim($gitConfig, '/') . '/.git';
        
        $this->rmDirOrFail($gitConfig);
        return $this;
    }
}
