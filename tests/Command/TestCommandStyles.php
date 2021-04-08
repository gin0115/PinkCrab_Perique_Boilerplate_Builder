<?php

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use PinkCrab\Plugin_Boilerplate_Builder\Command\CommandStyles;
use PinkCrab\Plugin_Boilerplate_Builder\Tests\Helper\OutputBuffer;

final class TestCommandStyles extends TestCase
{

    protected CommandStyles $styles;

    public function setUp(): void
    {
        $this->styles = new CommandStyles();
        parent::setUp();
    }

    public function testRegistersStyles(): void
    {
        $output = $this->styles->registerStyles(new OutputBuffer());
        $this->assertTrue($output->getFormatter()->hasStyle('lightpink'));
        $this->assertTrue($output->getFormatter()->hasStyle('darkpink'));
    }

    public function testCanAddStyle(): void
    {
        $this->styles->addStyle('white', new OutputFormatterStyle('#ffffff', null, array()));
        $output = $this->styles->registerStyles(new OutputBuffer());
        $this->assertTrue($output->getFormatter()->hasStyle('white'));
    }
}
