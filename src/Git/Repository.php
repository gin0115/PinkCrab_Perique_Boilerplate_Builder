<?php

/**
 * Handles all Repository operations
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Git;

use Cz\Git\GitRepository;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;

class Repository
{
    /**
     * Clones the repo and checks out to defined branch.
     *
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
        if (\file_exists($desination)) {
            exec("rm -rf {$desination}");
        }

        $repo = GitRepository::cloneRepository($sourceRepo, $desination);
        $repo->checkout($branch);

        return $this;
    }

    /**
     * Removes the .git file based on the dir.
     *
     * @param string $directory
     * @return self
     */
    public function removeGitConfig(string $directory): self
    {
        exec(sprintf(
            'rm -rf %s',
            str_ends_with($directory, '/.git')
                ? $directory
                : rtrim($directory, '/') . '/.git'
        ));
        return $this;
    }
}
