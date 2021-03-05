<?php

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Helper\QuestionHelper;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginBuilder;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginDetails;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginSetting;

final class TestPluginBuilder extends TestCase
{

    protected PluginBuilder $pluginBuilder;

    public function setUp(): void
    {
        $this->pluginBuilder = new PluginBuilder(
            (new PluginDetails())
            ->setPluginName(
                (new PluginSetting('testHandle'))
                    ->question('Test Question')
                    ->validation(fn(string $e): bool => $e === 'pass')
            )
        );
        parent::setUp();
    }

    public function testAskQuestions(): void
    {
        $this->expectOutputString('<darkpink> Test Question 
>');
        $helper = new HelperSet([
            QuestionHelper::class => new QuestionHelper()
        ]);

        // $helper->setInputStream($this->getInputStream('$input'));
        $commandTester = new CommandTester($command);
        $helper = $command->getHelper('question');
        
        $this->pluginBuilder->askQuestions(
            new StringInput('ANSWER'),
            new ConsoleOutput(),
            $helper
        );
    }

    private function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }
}
