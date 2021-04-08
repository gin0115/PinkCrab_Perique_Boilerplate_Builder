<?php

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Tests\Fixtures;

use Silly\Command\Command;
use Silly\Edition\PhpDi\Application;
use PinkCrab\Plugin_Boilerplate_Builder\Command\Build;
use PinkCrab\Plugin_Boilerplate_Builder\Git\Repository;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginBuilder;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginDetails;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginSetting;
use PinkCrab\Plugin_Boilerplate_Builder\Command\CommandStyles;
use PinkCrab\Plugin_Boilerplate_Builder\Application\PinkCrabPluginBuilder;

final class MockFactory
{

    /**
     * Returns a populated settings instance.
     * @param string[] $directories
     * @param string[] $files
     * @return Settings
     */
    public function settings(array $directories = [], array $files = []): Settings
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
        return $settings;
    }

    /**
     * Retrun a populate plugin details instance
     * @return PluginDetails
     */
    public function pluginDetails(?PluginSetting $settings = null): PluginDetails
    {
        $pluginDetails = new PluginDetails();
        $pluginDetails->setPluginName(
            $settings ?? ( new PluginSetting('plugin_name') )
                ->question('Please enter the plugin name')
                ->placeholder('##PLUGIN_NAME##')
                ->formatting('ucwords')
                ->validation(fn(string $e): bool => $e === 'pass')
            ->required()
        );
        return $pluginDetails;
    }
    
    /**
     * Returns an instance of the main App container.
     * @return Application
     */
    public function application(?Settings $settings = null, ?PluginDetails $pluginDetails = null): Application
    {
        $settings = $settings ?? $this->settings();
        $pluginDetails = $pluginDetails ?? $this->pluginDetails();
        return new PinkCrabPluginBuilder($settings, $pluginDetails);
    }

    /**
     * Return a PluginBuilder instance.
     * @param PluginDetails|null $details
     * @return PluginBuilder
     */
    public function pluginBuilder(?PluginDetails $details = null): PluginBuilder
    {
        return new PluginBuilder($details ?? $this->pluginDetails());
    }

    /**
     * Return a populate instance of the PluginBuilder.
     * Should only be used for unit tests, as has 1 question and no git url.
     * @return Command
     */
    public function buildCommandBasic(): Command
    {
        $pluginBuilder = new PluginBuilder($this->pluginDetails());
        $commandStyles = new CommandStyles();
        $gitRepository = new Repository();
        $application = $this->application();
        $settings = $this->settings();

        $build = new Build($pluginBuilder, $commandStyles, $gitRepository, $application, $settings);
    }
}
