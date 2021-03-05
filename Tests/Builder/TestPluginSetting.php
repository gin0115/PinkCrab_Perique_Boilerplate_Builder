<?php

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Tests\Builder;

use PHPUnit\Framework\TestCase;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginSetting;

final class TestPluginSetting extends TestCase
{
    public function pluginSettingInstance(): PluginSetting
    {
        return new PluginSetting('test');
    }

    /** @param mixed $value */
    public function isBlankString($value): bool
    {
        return \is_string($value) && \strlen(\trim($value)) === 0;
    }
    
    public function testCanCreateInstance(): void
    {
        $this->assertInstanceOf(PluginSetting::class, $this->pluginSettingInstance());
    }

    public function testGetHandle(): void
    {
        $this->assertEquals('test', $this->pluginSettingInstance()->getHandle());
    }

    public function testQuestion(): void
    {
        $setting = $this->pluginSettingInstance();
        $setting->question('Test Question');
        $this->assertEquals('Test Question', $setting->getQuestion());
    }

    public function testSubline(): void
    {
        $setting = $this->pluginSettingInstance();
        
        $this->assertFalse($setting->hasSubline());
        $this->asserttrue($this->isBlankString($setting->getSubLine()));
        
        $setting->subline('Subline');
        $this->assertTrue($setting->hasSubline());
        $this->assertEquals('Subline', $setting->getSubLine());
    }

    public function testRequired(): void
    {
        $setting = $this->pluginSettingInstance();
        $this->assertFalse($setting->getRequired());
        
        $setting->required(true);
        $this->assertTrue($setting->getRequired());
        
        $setting->required(false);
        $this->assertFalse($setting->getRequired());
        
        $setting->required(); // true by default
        $this->assertTrue($setting->getRequired());
    }

    public function testPlaceholder(): void
    {
        $setting = $this->pluginSettingInstance();
        
        $this->assertFalse($setting->hasSubline());
        $this->asserttrue($this->isBlankString($setting->getPlaceholder()));
        
        $setting->placeholder('##PLACEHOLDER##');
        $this->assertEquals('##PLACEHOLDER##', $setting->getPlaceholder());
    }

    public function testValidation(): void
    {
        $setting = $this->pluginSettingInstance();

        $setting->validation(function ($e): bool {
            return $e === 'pass';
        });

        // Passing
        $this->assertFalse($setting->withResponse('pass')->hasError());
        
        // Failed
        $this->assertTrue($setting->withResponse('fail')->hasError());
        $this->assertStringStartsWith(
            'Failed to set ',
            $setting->withResponse('fail')->getError()
        );
    }

    public function testError(): void
    {
        $setting = $this->pluginSettingInstance();
        $setting->error('Has Error');
        $this->assertTrue($setting->hasError());
        $this->assertSame('Has Error', $setting->getError());
    }

    public function testFormatting(): void
    {
        $setting = $this->pluginSettingInstance()
            ->formatting('strtoupper')
            ->withResponse('lowercase');
        
        $this->assertEquals('LOWERCASE', $setting->getResponse());
    }
}
