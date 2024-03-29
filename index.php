#!/usr/bin/env php
<?php

declare(strict_types=1);

use Silly\Edition\PhpDi\Application;
use PinkCrab\Plugin_Boilerplate_Builder\Command\Build;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginDetails;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginSetting;
use PinkCrab\Plugin_Boilerplate_Builder\Application\PinkCrabPluginBuilder;

require_once __DIR__ . '/vendor/autoload.php';

// Define settings.
$settings = new Settings();
$settings->setAppName('PinkCrab Perique Boilerplate Builder');
$settings->setAppVersion('0.3.0');
$settings->setBasePath(dirname(Phar::running(false), 1));
$settings->setTempPath(dirname(Phar::running(false), 1) . '/tmp');
$settings->setRepoUri('https://github.com/Pink-Crab/Perique-Boilerplate_basic.git');
$settings->setRepoBranch('seed_build');
$settings->setExcludedDirectories(array( '.github', '.git' ));
$settings->setExcludedFiles(array( 'composer.json', 'README.md', 'composer.lock', '.gitignore', '.gitkeep', 'phpcs.xml', 'phpunit.xml.dist', 'LICENSE' ));

// Define pluginDetails.
$pluginDetails = new PluginDetails();
$pluginDetails->setPluginName(
    ( new PluginSetting('plugin_name') )
        ->question('Please enter the plugin name')
        ->placeholder('##PLUGIN_NAME##')
        ->formatting('ucwords')
        ->required()
);

$pluginDetails->setPluginUrl(
    ( new PluginSetting('plugin_url') )
        ->question('What is your plugins url')
        ->subLine('Must be entered as full and valid url https://www.url.tld')
        ->placeholder('##PLUGIN_URL##')
        ->formatting('strtolower')
        ->validation(fn($e): bool => (bool) filter_var($e, FILTER_VALIDATE_URL))
);

$pluginDetails->setPluginDescription(
    ( new PluginSetting('plugin_description') )
        ->question('Your plugin description')
        ->subLine('This is used for both plugin.php and composer.json')
        ->placeholder('##PLUGIN_DESCRIPTION##')
);

$pluginDetails->setPluginVersion(
    ( new PluginSetting('plugin_version') )
        ->question('Your plugin version')
        ->placeholder('##PLUGIN_VERSION##')
);

$pluginDetails->setPluginTextdomain(
    ( new PluginSetting('plugin_textdomain') )
        ->question('Your plugin textdomain')
        ->placeholder('##PLUGIN_TEXTDOMAIN##')
);

/** AUTHOR DETAILS */
$pluginDetails->setAuthorName(
    ( new PluginSetting('author_name') )
        ->question('The primary author name')
        ->subLine('This is required, so please enter a valid name. You can remove after setup.')
        ->placeholder('##AUTHOR_NAME##')
        ->formatting('ucwords')
        ->required()
);

$pluginDetails->setAuthorEmail(
    ( new PluginSetting('author_email') )
        ->question('The primary author email')
        ->subLine('This is required, so please enter a valid name. You can remove after setup.')
        ->placeholder('##AUTHOR_EMAIL##')
        ->formatting('strtolower')
        ->validation(fn($e): bool => (bool) filter_var($e, FILTER_VALIDATE_EMAIL))
        ->required()
);

$pluginDetails->setAuthorUrl(
    ( new PluginSetting('author_url') )
        ->question('The primary author url')
        ->placeholder('##AUTHOR_URL##')
        ->formatting('strtolower')
        ->validation(fn($e): bool => (bool) filter_var($e, FILTER_VALIDATE_URL))
);

/** COMPOSER DETAILS */
$pluginDetails->setComposerName(
    ( new PluginSetting('composer_name') )
        ->question('Please enter a valid composer project name')
        ->placeholder('##PACKAGE_NAME##')
        ->subLine('Like achme/plugin-for-things')
        ->validation(
            function ($e): bool {
                $regex = '/^^[a-zA-Z\d\-\/]+$/';
                return (bool) \preg_match_all($regex, $e);
            }
        )
);

$pluginDetails->setPrimaryNamespace(
    ( new PluginSetting('primary_namespace') )
        ->question('The primary namespace for all code in src directory')
        ->placeholder('##NAMESPACE##')
        ->subLine('Like Achme\\Plugin_Namespace')
        ->validation(
            function ($e): bool {
                $regex = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff\\\\]*[a-zA-Z0-9_\x7f-\xff]$/';
                return (bool) \preg_match_all($regex, $e);
            }
        )
);

$pluginDetails->setScoperPrefix(
    ( new PluginSetting('scoper_prefix') )
        ->question('The unique PHP_Scoper prefix')
        ->placeholder('##SCOPER_PREFIX##')
        ->subLine('this must be as unique as possible. Like Achme_Plugins_Project_XX123')
        ->validation(
            function ($e): bool {
                $regex = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';
                return (bool) \preg_match_all($regex, $e);
            }
        )
);

$pluginDetails->setAutoloadDevPrefix(
    ( new PluginSetting('composer_dev_autoloader') )
        ->question('Please enter the prefix to apply to the dev autoloader')
        ->placeholder('##DEV_AUTLOADER_PREFIX##')
        ->subLine('must be valid chars for a php class name. Like achme_plugin_x_dev, this is used to identify the build autoload')
        ->validation(
            function ($e): bool {
                $regex = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';
                return (bool) \preg_match_all($regex, $e);
            }
        )
);

// Build the app.
$app = new PinkCrabPluginBuilder($settings, $pluginDetails);

$app->command('build [--dev] [--prod]', Build::class);
$app->setDefaultCommand('build');
$app->run();
