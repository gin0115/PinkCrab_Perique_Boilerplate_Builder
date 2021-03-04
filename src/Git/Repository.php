<?php

/**
 * Handles all Repository operations
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Git;

use Cz\Git\GitRepository;

class Repository {

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
	 * @return void
	 */
	public function removeGitConfig(): self {
		exec( sprintf( 'rm -rf %s', BOILERPLATE_REPO_TEMP_PATH . '/.git' ) );
		return $this;
	}
}








