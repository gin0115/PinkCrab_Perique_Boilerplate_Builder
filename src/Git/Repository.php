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
	) {
		$repo = GitRepository::cloneRepository( $sourceRepo, $desination );
		$repo->checkout( $branch );
	}
}








