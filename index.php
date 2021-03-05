<?php

declare(strict_types=1);

use Silly\Edition\PhpDi\Application;
use PinkCrab\Plugin_Boilerplate_Builder\Command\Build;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;
use PinkCrab\Plugin_Boilerplate_Builder\Application\PinkCrabPluginBuilder;

require_once __DIR__ . '/vendor/autoload.php';

// define('PLUGIN_BASE_PATH', __DIR__);
// define('BOILERPLATE_REPO_TEMP_PATH', PLUGIN_BASE_PATH . '/tmp');
// define('BOILERPLATE_REPO', 'https://github.com/Pink-Crab/Framework_Plugin_Boilerplate.git');
// define('BOILERPLATE_REPO_BRANCH', 'seed_build');
// define('EXCLUDED_FILES', array( 'README.md', 'composer.lock', '.gitignore', '.gitkeep', 'phpcs.xml', 'phpunit.xml.dist', 'LICENSE' ));
// define('EXCLUDED_DIRECTORIES', array( '.github', '.git', 'build-tools' ));

// Define settings.
$settings = new Settings();
$settings->setAppName('PinkCrab Plugin Builder');
$settings->setAppVersion('0.2.0');
$settings->setBasePath(__DIR__);
$settings->setTempPath(__DIR__ . '/tmp');
$settings->setRepoUri('https://github.com/Pink-Crab/Framework_Plugin_Boilerplate.git');
$settings->setRepoBranch('seed_build');
$settings->setExcludedDirectories(['.github', '.git', 'build-tools']);
$settings->setExcludedFiles(['README.md', 'composer.lock', '.gitignore', '.gitkeep', 'phpcs.xml', 'phpunit.xml.dist', 'LICENSE' ]);

$app = new PinkCrabPluginBuilder($settings);
$app->command('build', Build::class);


$app->run();
