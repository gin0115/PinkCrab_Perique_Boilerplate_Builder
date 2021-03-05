<?php

/**
 * Main build command.
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Command;

use Silly\Command\Command;
use Silly\Edition\PhpDi\Application;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginBuilder;
use PinkCrab\Plugin_Boilerplate_Builder\Git\Repository as GitRepository;

class Build extends Command
{

    protected PluginBuilder $pluginBuilder;
    protected Application $app;
    protected CommandStyles $styles;
    protected GitRepository $gitRepository;
    protected Settings $settings;

    public function __construct(
        PluginBuilder $pluginBuilder,
        CommandStyles $styles,
        GitRepository $gitRepository,
        Application $app,
        Settings $settings
    ) {
        $this->pluginBuilder = $pluginBuilder;
        $this->app           = $app;
        $this->styles        = $styles;
        $this->gitRepository = $gitRepository;
        $this->settings = $settings;
    }

    /**
     * @var string
     */
    private string $logo = <<<LOGO

    
                       __       __
                      / <`     '> \
                     (  / @   @ \  )
                      \(_ _\_/_ _)/
                    (\ `-/ P C \-' /)
                     "===\ P B /==="
                      .==')_=_(`==.    
                     ' .='     `=.
        

LOGO;

    /**
     * @return string
     */
    private function title(): string
    {
        return <<<TITLE

            	{$this->settings->getAppName()}
            	  v{$this->settings->getAppVersion()}

TITLE;
    }

    /**
     * @param OutputInterface $output
     * @param InputInterface $input
     * @return void
     */
    public function __invoke(OutputInterface $output, InputInterface $input)
    {

        // Show intro screen.
        $output = $this->styles->registerStyles($output);

        $output->writeln('<darkpink>' . $this->logo . '</>');
        $output->writeln('<lightpink>' . $this->title() . PHP_EOL . '</>');

        // Ask questions
        // $this->pluginBuilder->askQuestions(
        //     $input,
        //     $output,
        //     $this->app->getHelperSet()
        // );

        // // Abort if any errors.
        // if ($this->pluginBuilder->hasErrors()) {
        //     $output->writeln(PHP_EOL);
        //     foreach ($this->pluginBuilder->getErrors() as $error) {
        //         $output->writeln("<error>{$error}</>");
        //     }
        //     $output->writeln('<error>BUILD TERMINATED</>');
        //     $output->writeln(PHP_EOL);
        //     exit;
        // }

        // Clone repo & remove .git
        $this->gitRepository->clone(
            $this->settings->getRepoUri(),
            $this->settings->getTempPath(),
            $this->settings->getRepoBranch()
        )->removeGitConfig($this->settings->getTempPath() . '/.git');

        // Replace all placehoders.
    }
}
