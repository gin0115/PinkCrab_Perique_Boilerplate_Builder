<?php

/**
 * Application container.
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Application;

use DI\Container;
use DI\ContainerBuilder;
use Silly\Edition\PhpDi\Application;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;

class PinkCrabPluginBuilder extends Application
{
    /**
     * @var Settings
     */
    protected $settings;
    
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
        parent::__construct($settings->getAppName(), $settings->getAppVersion(), null);
    }
    
    protected function createContainer(): Container
    {
        $container = ( new ContainerBuilder() )
            ->addDefinitions(
                [
                    Application::class => $this,
                    Settings::class => $this->settings
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
}
