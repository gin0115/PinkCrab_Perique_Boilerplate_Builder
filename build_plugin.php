#!/usr/bin/env php
<?php

define( 'REPO_URI', 'https://github.com/Pink-Crab/Framework_Plugin_Boilerplate.git' );
define( 'BASE_PATH', __DIR__ );
define( 'EXCLUDED_FILES', array( 'README.md', 'composer.lock', '.gitignore', '.gitkeep', 'phpcs.xml', 'phpunit.xml.dist', 'LICENSE' ) );
define( 'EXCLUDED_DIRECTORIES', array( '.github', '.git', 'build-tools' ) );

$questions = array(
	'plugin_name'             => array(
		'question' => 'What is your plugin name',
		'sub_line' => 'required',
		'required' => true,
		'find'     => '##PLUGIN_NAME##',
		'replace'  => '',
	),
	'plugin_url'              => array(
		'question' => 'What is your plugins url',
		'sub_line' => '',
		'required' => false,
		'find'     => '##PLUGIN_URL##',
		'replace'  => '',
	),
	'plugin_description'      => array(
		'question' => 'Your plugin description',
		'sub_line' => 'this is used in plugin.php and composer.json',
		'required' => false,
		'find'     => '##PLUGIN_DESCRIPTION##',
		'replace'  => '',
	),
	'plugin_version'          => array(
		'question' => 'Your plugin version',
		'sub_line' => '',
		'required' => false,
		'find'     => '##PLUGIN_VERSION##',
		'replace'  => '',
	),
	'plugin_textdomain'       => array(
		'question' => 'Your plugin textdomain',
		'sub_line' => '',
		'required' => false,
		'find'     => '##PLUGIN_TEXTDOMAIN##',
		'replace'  => '',
	),
	'author_name'             => array(
		'question' => 'The primary author name',
		'sub_line' => '(additonal can be added manually afterwards)',
		'required' => false,
		'find'     => '##AUTHOR_NAME##',
		'replace'  => '',
	),
	'author_email'            => array(
		'question' => 'The primary author email',
		'sub_line' => '',
		'required' => false,
		'find'     => '##AUTHOR_EMAIL##',
		'replace'  => '',
	),
	'author_url'              => array(
		'question' => 'The primary author url',
		'sub_line' => '',
		'required' => false,
		'find'     => '##AUTHOR_URL##',
		'replace'  => '',
	),
	'primary_namespace'       => array(
		'question' => 'The primary namespace for all code in src dir',
		'sub_line' => 'Like Achme\\Plugin_Namespace\\',
		'required' => true,
		'find'     => '##NAMESPACE##',
		'replace'  => '',
	),
	'wp_namespace'            => array(
		'question' => 'The namespace used for plugin activation and deactivation hooks',
		'sub_line' => 'should differ form primary namespace. Like Achme\WP\Plugin_Namespace',
		'required' => true,
		'find'     => '##NAMESPACE_WP##',
		'replace'  => '',
	),
	'scoper_prefix'           => array(
		'question' => 'The unique PHP_Scoper prefix',
		'sub_line' => 'this must be as unique as possilble. Like Achme_Plugins_Project_XX123',
		'required' => true,
		'find'     => '##SCOPER_PREFIX##',
		'replace'  => '',
	),
	'composer_name'           => array(
		'question' => 'Please enter a valid composer project name',
		'sub_line' => 'Like achme/plugin-for-things',
		'required' => true,
		'find'     => '##PACKAGE_NAME##',
		'replace'  => '',
	),
	'composer_dev_autoloader' => array(
		'question' => 'Please enter the prefix to apply to the dev autoloader',
		'sub_line' => 'must be valid chars for phpclass name. Like achme_plugin_x_dev',
		'required' => true,
		'find'     => '##DEV_AUTLOADER_PREFIX##',
		'replace'  => '',
	),
);



// Entry point
function entry( array $rules ): void {
	// Get all values to replace with
	$rules = ask_setup_questions( $rules );
	verify_setup_responses( $rules );

	// Setup the repo for search & repalce.
	clone_repo();
	prepare_for_find_and_replace();

	// // Process files.
	get_all_files();
	search_replace( $rules );

	// Move plugin and remove generator.
	move_plugin();

	dividing_line();
	println( '* - Plugin should now be setup and ready to go.' );
	println( '* - You will need to ensure all namespaces in composer.json have double \\.' );
	dividing_line();
	println( '* - Now just run bash build.sh and you will be able to test your plugin.' );
	dividing_line();
}

// Ask question(s) and get the response text.
function user_prompt( string ...$questions ): string {
	foreach ( $questions as $question ) {
		if ( empty( $question ) ) {
			continue;
		}
		println( $question );
	}
	print( '> ' );
	$input_pointer = fopen( 'php://stdin', 'r' );
	$response      = fgets( $input_pointer );
	fclose( $input_pointer );
	return trim( $response );
}

// Loops through all questions and get results.
function ask_setup_questions( array $questions ): array {
	// Loop through all questions and get answers
	foreach ( $questions as $key => $question ) {
		dividing_line();

		$response = user_prompt( $question['question'], $question['sub_line'] );
		// Throw error is question has a required resonse.
		if ( empty( $response ) && $question['required'] ) {
			abort_script( "{$question['question']} is required, please start again" );
		}

		$questions[ $key ]['replace'] = $response;
	}

	return $questions;
}

// Verfifes the answer submitted with user input
function verify_setup_responses( array $questions ): bool {
	println( PHP_EOL );
	dividing_line();
	println( '* - Please ensure your details are correct.' );
	dividing_line();
	foreach ( $questions as $question ) {
		println( "* - {$question['question']} --> {$question['replace']}" );
		dividing_line();
	}

	$response = user_prompt( 'Are these details correct (y|yes)?' );
	if ( ! in_array( strtolower( $response ), array( 'y', 'yes' ), true ) ) {
		abort_script( 'You chose to abort the script, please try again.' );
	}

	return true;
}

// Used to abort the script.
function abort_script( string $message ): void {
	print( PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . '!!!!! ABORTING !!!!!' . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL );
	trigger_error( $message, E_ERROR );
	exit;
}

// Clones the repo into the current dir.
function clone_repo(): void {
	dividing_line();
	println( '* - Cloning into Plugin Boilerplate Repo' );
	println( '* - From: ' . REPO_URI );
	dividing_line();

	$output = array();
	$return = 0;
	exec( 'rm -rf tmp' );
	exec( sprintf( 'git clone %s tmp', REPO_URI ), $output, $return );
	exec( 'cd tmp && git checkout feature/create-placeholder-version-for-seed-build  && cd ..', $output );

	// Print any output.
	foreach ( $output as $line ) {
		println( '* - ' . $line );
	}
	dividing_line();

	// Create patchers folder, incase cleared by gitignore
	@mkdir( BASE_PATH . '/tmp/patchers' );

}

// Remove .git and preps for scan of files.
function prepare_for_find_and_replace(): void {
	dividing_line();
	println( '* - Removing the current .git files' );
	exec( 'rm -rf tmp/.git' );
	dividing_line();
}


// Search and replaces for all rules passed in the string.
function search_replace( array $search_replace ): void {
	$files = get_all_files();

	dividing_line();
	println( '* - Compiling Translations' );

	// Compile all translations.
	$translations = array_reduce(
		$search_replace,
		function( $carry, $rule ) {
			$carry[ $rule['find'] ] = $rule['replace'];
			return $carry;
		},
		array()
	);

	// Update all files.
	foreach ( $files as $file_path ) {
		$contents = file_get_contents( $file_path );
		// Replace what is needed.
		$contents = strtr( $contents, $translations );
		file_put_contents( $file_path, $contents );
		dividing_line();
		println( "* - Processed {$file_path}" );
	}
}

// Returns all the files to be checked.
function get_all_files(): array {
	$directory = new \RecursiveDirectoryIterator( 'tmp', RecursiveDirectoryIterator::SKIP_DOTS );
	$iterator  = new \RecursiveIteratorIterator( $directory );

	// Get all files.
	$files = array();
	foreach ( $iterator as $info ) {
		if ( $info->isFile()
		&& ! in_array( $info->getFilename(), EXCLUDED_FILES, true )
		) {
			$files[] = $info->getPathname();

		}
	}

	// Remove excluded direcories.
	return array_filter(
		$files,
		function( $e ): bool {
			foreach ( EXCLUDED_DIRECTORIES as $dir ) {
				if ( string_starts_with( "{$e}", "tmp/{$dir}/" ) ) {
					return false;
				}
			}
			return true;
		}
	);
}

// Moves the contents of the cloned plugin to the root.
function move_plugin(): void {
	dividing_line();
	println( '* - Moving plugin from temp dir to current base.' );
	exec( 'mv ' . BASE_PATH . '/tmp/*' . ' ' . BASE_PATH );

	// Move git files.
	exec( 'mv ' . BASE_PATH . '/tmp/.gitignore' . ' ' . BASE_PATH . '/.gitignore' );
	exec( 'mv ' . BASE_PATH . '/tmp/.github' . ' ' . BASE_PATH . '/.github' );

}

function cleanup_artifacts() {
	dividing_line();
	println( '*- Removinig old temp directory' );
	rmdir( BASE_PATH . '/tmp' );
}

// Pollyfill for startsWith
function string_starts_with( $haystack, $needle ) {
	$length = strlen( $needle );
	return substr( $haystack, 0, $length ) === $needle;
}

// Adds a dividing line to the output.
function dividing_line(): void {
	println( '* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ' );
}

// Print line
function println( $print ): void {
	print (string) $print . PHP_EOL;
}


// Initialiase.
entry( $questions );
