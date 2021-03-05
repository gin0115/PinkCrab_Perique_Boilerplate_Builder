<?php

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Tests\Application;

use PHPUnit\Framework\TestCase;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginDetails;
use PinkCrab\Plugin_Boilerplate_Builder\Application\PinkCrabPluginBuilder;

final class TestPinkCrabPluginBuilder extends TestCase
{
    protected PinkCrabPluginBuilder $app;
    
    public function setUp(): void
    {
        $settings = new Settings();
        $pluginDetails = new PluginDetails();

        $this->app = new PinkCrabPluginBuilder($settings, $pluginDetails);
    }
    
    public function testCanConstructAppContainer(): void
    {
        $this->assertInstanceOf(PinkCrabPluginBuilder::class, $this->app);
    }

    public function testGetSettings(): void
    {
        $this->assertInstanceOf(Settings::class, $this->app->settings());
    }

    public function testGetPluginDetails(): void
    {
        $this->assertInstanceOf(PluginDetails::class, $this->app->pluginDetails());
    }
}
