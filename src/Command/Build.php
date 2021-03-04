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
use PinkCrab\Plugin_Boilerplate_Builder\Git\Repository;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginBuilder;

class Build extends Command {

	protected PluginBuilder $pluginBuilder;
	protected Application $app;
	protected CommandStyles $styles;
	protected Repository $gitRepository;

	public function __construct(
		PluginBuilder $pluginBuilder,
		CommandStyles $styles,
		Repository $gitRepository,
		Application $app
	) {
		$this->pluginBuilder = $pluginBuilder;
		$this->app           = $app;
		$this->styles        = $styles;
		$this->gitRepository = $gitRepository;
	}

	private static $logo = <<<LOGO

    
                       __       __
                      / <`     '> \
                     (  / @   @ \  )
                      \(_ _\_/_ _)/
                    (\ `-/ P C \-' /)
                     "===\ B P /==="
                      .==')___(`==.    
                     ' .='     `=.
        

LOGO;

	private static $title = <<<TITLE

            PinkCrab Plugin Framework Builder
              v0.0.1

TITLE;

	public function __invoke( $name, OutputInterface $output, Input $input ) {

		// Show intro screen.
		$output = $this->styles->registerStyles( $output );

		// $output->getFormatter()->setStyle( 'darkpink', $this->darkPinkStyle() );
		$output->writeln( '<darkpink>' . self::$logo . '</>' );
		$output->writeln( '<lightpink>' . self::$title . PHP_EOL . '</>' );

		// Ask questions
		$this->pluginBuilder->askQuestions(
			$input,
			$output,
			$this->app->getHelperSet()
		);

		// Abort if any errors.
		if ( $this->pluginBuilder->hasErrors() ) {
			$output->writeln( PHP_EOL );
			foreach ( $this->pluginBuilder->getErrors() as $error ) {
				$output->writeln( "<error>{$error}</>" );

			}
			$output->writeln( '<error>BUILD TERMINATED</>' );
			$output->writeln( PHP_EOL );
			exit;
		}

		// Clone repo & remove .git
		$this->gitRepository->clone(
			BOILERPLATE_REPO,
			BOILERPLATE_REPO_TEMP_PATH,
			BOILERPLATE_REPO_BRANCH
		)->removeGitConfig();
	}
}
