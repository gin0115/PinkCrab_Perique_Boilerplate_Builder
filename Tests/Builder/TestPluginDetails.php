<?php

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Tests\Builder;

use Exception;
use PHPUnit\Framework\TestCase;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginDetails;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginSetting;

final class TestPluginDetails extends TestCase
{

    protected PluginDetails $pluginDetails;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->pluginDetails = new PluginDetails();
    }

    public function testToArray(): void
    {
        $this->pluginDetails->setPluginName(new PluginSetting('test'));
        $asArray = $this->pluginDetails->toArray();
        $this->assertIsArray($asArray);
    }

    public function testPluginName(): void
    {
        $this->pluginDetails->setPluginName(new PluginSetting('test'));
        $instancePluginName = $this->pluginDetails->getPluginName();
        $this->assertInstanceOf(PluginSetting::class, $instancePluginName);
    }

    public function testPrimaryNamespace(): void
    {
        $this->pluginDetails->setPrimaryNamespace(new PluginSetting('test'));
        $instancePrimaryNamespace = $this->pluginDetails->getPrimaryNamespace();
        $this->assertInstanceOf(PluginSetting::class, $instancePrimaryNamespace);
    }

    public function testPluginUrl(): void
    {
        $this->pluginDetails->setPluginUrl(new PluginSetting('test'));
        $instancePluginUrl = $this->pluginDetails->getPluginUrl();
        $this->assertInstanceOf(PluginSetting::class, $instancePluginUrl);
    }

    public function testPluginDescription(): void
    {
        $this->pluginDetails->setPluginDescription(new PluginSetting('test'));
        $instancePluginDescription = $this->pluginDetails->getPluginDescription();
        $this->assertInstanceOf(PluginSetting::class, $instancePluginDescription);
    }

    public function testPluginTextdomain(): void
    {
        $this->pluginDetails->setPluginTextdomain(new PluginSetting('test'));
        $instancePluginTextdomain = $this->pluginDetails->getPluginTextdomain();
        $this->assertInstanceOf(PluginSetting::class, $instancePluginTextdomain);
    }

    public function testPluginVersion(): void
    {
        $this->pluginDetails->setPluginVersion(new PluginSetting('test'));
        $instancePluginVersion = $this->pluginDetails->getPluginVersion();
        $this->assertInstanceOf(PluginSetting::class, $instancePluginVersion);
    }

    public function testAuthorName(): void
    {
        $this->pluginDetails->setAuthorName(new PluginSetting('test'));
        $instanceAuthorName = $this->pluginDetails->getAuthorName();
        $this->assertInstanceOf(PluginSetting::class, $instanceAuthorName);
    }

    public function testAuthorEmail(): void
    {
        $this->pluginDetails->setAuthorEmail(new PluginSetting('test'));
        $instanceAuthorEmail = $this->pluginDetails->getAuthorEmail();
        $this->assertInstanceOf(PluginSetting::class, $instanceAuthorEmail);
    }

    public function testAuthorUrl(): void
    {
        $this->pluginDetails->setAuthorUrl(new PluginSetting('test'));
        $instanceAuthorUrl = $this->pluginDetails->getAuthorUrl();
        $this->assertInstanceOf(PluginSetting::class, $instanceAuthorUrl);
    }

    public function testWPNamespace(): void
    {
        $this->pluginDetails->setWPNamespace(new PluginSetting('test'));
        $instanceWPNamespace = $this->pluginDetails->getWPNamespace();
        $this->assertInstanceOf(PluginSetting::class, $instanceWPNamespace);
    }

    public function testScoperPrefix(): void
    {
        $this->pluginDetails->setScoperPrefix(new PluginSetting('test'));
        $instanceScoperPrefix = $this->pluginDetails->getScoperPrefix();
        $this->assertInstanceOf(PluginSetting::class, $instanceScoperPrefix);
    }

    public function testComposerName(): void
    {
        $this->pluginDetails->setComposerName(new PluginSetting('test'));
        $instanceComposerName = $this->pluginDetails->getComposerName();
        $this->assertInstanceOf(PluginSetting::class, $instanceComposerName);
    }

    public function testAutoloadDevPrefix(): void
    {
        $this->pluginDetails->setAutoloadDevPrefix(new PluginSetting('test'));
        $instanceAutoloadDevPrefix = $this->pluginDetails->getAutoloadDevPrefix();
        $this->assertInstanceOf(PluginSetting::class, $instanceAutoloadDevPrefix);
    }

    public function testSetResponse(): void
    {
        $this->pluginDetails->setAutoloadDevPrefix(new PluginSetting('test'));
        $this->pluginDetails->setResponse('autoloadDevPrefix', 'response');
        $this->assertEquals(
            'response',
            $this->pluginDetails->getAutoloadDevPrefix()->getResponse()
        );
    }

    public function testSetReponseThrowsWithInvlaidProperty(): void
    {
        $this->expectException(Exception::class);
        $this->pluginDetails->setResponse('invlaidProperty', 'ERROR');
    }
}
