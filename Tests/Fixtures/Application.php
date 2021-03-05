<?php

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Tests\Fixtures;

use Silly\Edition\PhpDi\Application;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginDetails;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginSetting;
use PinkCrab\Plugin_Boilerplate_Builder\Application\PinkCrabPluginBuilder;

final class ApplicationProvider
{

    /**
     * @param string[] $directories
     * @param string[] $files
     * @return \Silly\Edition\PhpDi\Application
     */
    public function instance(array $directories = [], array $files = []): Application
    {
        $settings = new Settings();
        $settings->setAppName('Test Application');
        $settings->setAppVersion('1.2.3');
        $settings->setBasePath(__DIR__);
        $settings->setTempPath(__DIR__ . '/tmp');
        $settings->setRepoUri('url');
        $settings->setRepoBranch('reoo');
        $settings->setExcludedDirectories($directories);
        $settings->setExcludedFiles($files);

        // Define pluginDetails.
        $pluginDetails = new PluginDetails();
        $pluginDetails->setPluginName(
            ( new PluginSetting('plugin_name') )
                ->question('Please enter the plugin name')
                ->placeholder('##PLUGIN_NAME##')
                ->formatting('ucwords')
                ->required()
        );

        return new PinkCrabPluginBuilder($settings, $pluginDetails);
    }
}
