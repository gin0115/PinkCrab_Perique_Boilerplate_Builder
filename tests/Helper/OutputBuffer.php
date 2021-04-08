<?php

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Tests\Helper;

use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class OutputBuffer extends Output implements OutputInterface
{
    /** @var string[] */
    public array $output = [];

    protected function doWrite(string $message, bool $newline): void
    {
        $this->output[] = sprintf("%s%s", $message, $newline ? "\n" : '');
    }

    public function clear(): void
    {
        $this->output = [];
    }

    public function last(): string
    {
        return count($this->output) === 0
            ? '' : end($this->output);
    }
}
