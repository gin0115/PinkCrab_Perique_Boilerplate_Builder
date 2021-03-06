<?php

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Tests\Builder;

use PHPUnit\Framework\TestCase;
use PinkCrab\PHPUnit_Helpers\Reflection;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginBuilder;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginDetails;
use PinkCrab\Plugin_Boilerplate_Builder\Tests\Fixtures\MockFactory;

final class TestPluginBuilder extends TestCase
{

    protected PluginBuilder $pluginBuilder;

    public function setUp(): void
    {
        $this->pluginBuilder = (new MockFactory())->pluginBuilder();
        parent::setUp();
    }

    public function mockDetailsWithErrors(): void
    {
        /** @var PluginDetails */
        $details = Reflection::get_private_property($this->pluginBuilder, 'pluginDetails');
        $setting = $details->getPluginName()->withResponse('fail');
        $details->setPluginName($setting);
    }

    public function testHasErrors(): void
    {
        $this->mockDetailsWithErrors();
        $this->assertTrue($this->pluginBuilder->hasErrors());
    }

    public function testCanGetErrors(): void
    {
        $this->mockDetailsWithErrors();
        $errors = $this->pluginBuilder->getErrors();
        $this->assertGreaterThan(0, count($errors));
        $this->assertArrayHasKey('pluginName', $errors);
        $this->assertStringContainsString('Failed to set plugin_name', $errors['pluginName']);
        $this->assertStringContainsString('\'fail\' was passed', $errors['pluginName']);
    }
}
