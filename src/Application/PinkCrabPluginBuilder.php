<?php

/**
 * Application container.
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Application;

use DI\Container;
use DI\ContainerBuilder;
use Silly\Edition\PhpDi\Application;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginDetails;

class PinkCrabPluginBuilder extends Application
{
    /**
     * @var Settings
     */
    protected $settings;
    /**
     * @var PluginDetails
     */
    protected $pluginDetails;

    /**
     * Creates an instance of the App.
     *
     * @param Settings $settings
     * @param PluginDetails $pluginDetails
     */
    public function __construct(Settings $settings, PluginDetails $pluginDetails)
    {
        $this->settings = $settings;
        $this->pluginDetails = $pluginDetails;
        parent::__construct($settings->getAppName(), $settings->getAppVersion(), null);
    }

    /**
     * Creates custom instance of container with bindings.
     *
     * @return \DI\Container
     */
    protected function createContainer(): Container
    {
        $container = ( new ContainerBuilder() )
            ->addDefinitions(
                [
                    Application::class => $this,
                    Settings::class => $this->settings,
                    PluginDetails::class => $this->pluginDetails
                ]
            )
            ->build();
            return $container;
    }

    /**
     * Get the value of settings
     *
     * @return Settings
     */
    public function settings(): Settings
    {
        return $this->settings;
    }

    /**
     * Get the value of pluginDetails
     *
     * @return PluginDetails
     */
    public function pluginDetails(): PluginDetails
    {
        return $this->pluginDetails;
    }
}
