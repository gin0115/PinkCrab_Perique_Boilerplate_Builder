<?php

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Tests\Application;

use PHPUnit\Framework\TestCase;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;

final class TestSettings extends TestCase
{

    public function testAppVersion(): void
    {
        $settings = new Settings();
        $this->assertEquals('UNKNOWN', $settings->getAppVersion());
        $settings->setAppVersion('TEST');
        $this->assertEquals('TEST', $settings->getAppVersion());
    }

    public function testAppName(): void
    {
        $settings = new Settings();
        $this->assertEquals('UNKNOWN', $settings->getAppName());
        $settings->setAppName('TEST');
        $this->assertEquals('TEST', $settings->getAppName());
    }
    
    public function testBasePath(): void
    {
        $settings = new Settings();
        // Will return the expected basepath if nothing set!
        $this->assertNotEmpty($settings->getBasePath());
        $settings->setBasePath('TEST');
        $this->assertEquals('TEST', $settings->getBasePath());
    }

    public function testTempPath(): void
    {
        $settings = new Settings();
        // Will return the expected basepath if nothing set!
        $this->assertNotEmpty($settings->getTempPath());
        $settings->setTempPath('TEST');
        $this->assertEquals('TEST', $settings->getTempPath());
    }

    public function testRepoUri(): void
    {
        $settings = new Settings();
        $this->assertEmpty($settings->getRepoUri());
        $settings->setRepoUri('TEST');
        $this->assertEquals('TEST', $settings->getRepoUri());
    }


    public function testRepoBranch(): void
    {
        $settings = new Settings();
        $this->assertEquals('master', $settings->getRepoBranch());
        $settings->setRepoBranch('TEST');
        $this->assertEquals('TEST', $settings->getRepoBranch());
    }

    public function testExcludedFiles(): void
    {
        $settings = new Settings();
        $this->assertCount(0, $settings->getExcludedFiles());
        $settings->setExcludedFiles(['TEST' => 'FOO']);
        $this->assertCount(1, $settings->getExcludedFiles());
    }

    public function testExcludedDirectories(): void
    {
        $settings = new Settings();
        $this->assertCount(0, $settings->getExcludedDirectories());
        $settings->setExcludedDirectories(['TEST' => 'FOO']);
        $this->assertCount(1, $settings->getExcludedDirectories());
    }
}
