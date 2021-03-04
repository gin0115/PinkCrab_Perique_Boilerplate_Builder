<?php

/**
 * Handles all Repository operations
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Git;

use Cz\Git\GitRepository;

class Repository {

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
		if ( \file_exists( $desination ) ) {
			exec( "rm -rf {$desination}" );
		}

		$repo = GitRepository::cloneRepository( $sourceRepo, $desination );
		$repo->checkout( $branch );

		return $this;
	}

	/**
	 * Removes the .git file
	 *
	 * @return self
	 */
	public function removeGitConfig(): self {
		exec( sprintf( 'rm -rf %s', BOILERPLATE_REPO_TEMP_PATH . '/.git' ) );
		return $this;
	}
}








