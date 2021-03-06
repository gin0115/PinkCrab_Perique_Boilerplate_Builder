<?php

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Tests\Helper;

use Symfony\Component\Console\Input\StringInput;
use PinkCrab\Plugin_Boilerplate_Builder\Tests\Helper\OutputBuffer;

trait CommandTrait
{
    private function assertOutputIs($command, $expected)
    {
        $output = new OutputBuffer();
        $this->application->run(new StringInput($command), $output);
        $this->assertEquals($expected, $output->last());
    }
}
