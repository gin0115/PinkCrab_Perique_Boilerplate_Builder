<?php

declare(strict_types=1);

use Silly\Edition\PhpDi\Application;
use PinkCrab\Plugin_Boilerplate_Builder\Command\Build;

require_once __DIR__ . '/vendor/autoload.php';

define( 'PLUGIN_BASE_PATH', __DIR__ );
define( 'BOILERPLATE_REPO_TEMP_PATH', PLUGIN_BASE_PATH . '/tmp' );
define( 'BOILERPLATE_REPO', 'https://github.com/Pink-Crab/Framework_Plugin_Boilerplate.git' );
define( 'BOILERPLATE_REPO_BRANCH', 'seed_build' );
define( 'EXCLUDED_FILES', array( 'README.md', 'composer.lock', '.gitignore', '.gitkeep', 'phpcs.xml', 'phpunit.xml.dist', 'LICENSE' ) );
define( 'EXCLUDED_DIRECTORIES', array( '.github', '.git', 'build-tools' ) );


$app = new Application();
$app->command( 'build [name]', Build::class );


$app->run();
