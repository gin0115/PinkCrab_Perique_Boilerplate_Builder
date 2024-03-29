<?php

/**
 * Main build command.
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Command;

use Silly\Command\Command;
use Silly\Edition\PhpDi\Application;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PinkCrab\Plugin_Boilerplate_Builder\Helper\Strings;
use PinkCrab\Plugin_Boilerplate_Builder\Application\Settings;
use PinkCrab\Plugin_Boilerplate_Builder\FileIO\FilePopulator;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginBuilder;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginDetails;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginSetting;
use PinkCrab\Plugin_Boilerplate_Builder\Git\Repository as GitRepository;

class Build extends Command {



	protected PluginBuilder $pluginBuilder;
	protected Application $app;
	protected CommandStyles $styles;
	protected GitRepository $gitRepository;
	protected Settings $settings;
	protected FilePopulator $filePopulator;
	protected Filesystem $fileSystem;

	public function __construct(
		Application $app,
		Settings $settings,
		CommandStyles $styles,
		PluginBuilder $pluginBuilder,
		GitRepository $gitRepository,
		Filesystem $fileSystem,
		FilePopulator $filePopulator
	) {
		$this->app           = $app;
		$this->styles        = $styles;
		$this->settings      = $settings;
		$this->gitRepository = $gitRepository;
		$this->pluginBuilder = $pluginBuilder;
		$this->fileSystem    = $fileSystem;
		$this->filePopulator = $filePopulator;
	}

	/**
	 * @var string
	 */
	private string $logo = <<<LOGO

            ______  _         _     _____               _             ______              _                         
            | ___ \(_)       | |   /  __ \             | |            | ___ \            (_)                        
            | |_/ / _  _ __  | | __| /  \/ _ __   __ _ | |__          | |_/ /  ___  _ __  _   __ _  _   _   ___     
            |  __/ | || '_ \ | |/ /| |    | '__| / _` || '_ \         |  __/  / _ \| '__|| | / _` || | | | / _ \    
            | |    | || | | ||   < | \__/\| |   | (_| || |_) |        | |    |  __/| |   | || (_| || |_| ||  __/    
            \_|    |_||_| |_||_|\_\ \____/|_|    \__,_||_.__/         \_|     \___||_|   |_| \__, | \__,_| \___|    
                                                                                                | |                 
        ______         _  _                     _         _                 ______         _  _ |_|  _             
        | ___ \       (_)| |                   | |       | |                | ___ \       (_)| |    | |            
        | |_/ /  ___   _ | |  ___  _ __  _ __  | |  __ _ | |_   ___         | |_/ / _   _  _ | |  __| |  ___  _ __ 
        | ___ \ / _ \ | || | / _ \| '__|| '_ \ | | / _` || __| / _ \        | ___ \| | | || || | / _` | / _ \| '__|
        | |_/ /| (_) || || ||  __/| |   | |_) || || (_| || |_ |  __/        | |_/ /| |_| || || || (_| ||  __/| |   
        \____/  \___/ |_||_| \___||_|   | .__/ |_| \__,_| \__| \___|        \____/  \__,_||_||_| \__,_| \___||_|   
                                        | |                                                                        
                                        |_|                                                                                                                                        
LOGO;

	/**
	 * @return string
	 */
	private function title(): string {
		return <<<TITLE
            	                                                                                             v{$this->settings->getAppVersion()}
TITLE;
	}

	/**
	 * @param OutputInterface $output
	 * @param Input $input
	 * @return void
	 */
	public function __invoke(
		bool $dev = false,
		bool $prod = false,
		OutputInterface $output, //phpcs:disable PEAR.Functions.ValidDefaultValue.NotAtEnd -- no control over arg order
		Input $input
	) {

		// Show intro screen.
		$output = $this->styles->registerStyles( $output );
		$output->writeln( '<darkpink>' . $this->logo . '</>' );
		$output->writeln( '<lightpink>' . $this->title() . PHP_EOL . '</>' );

		// Ask questions
		$this->pluginBuilder->askQuestions( $input, $output, $this->app->getHelperSet() );

		// Abort if any errors.
		$this->verifyPluginDetails( $output );

		 // Compose translations.
		$translations = $this->pluginBuilder->pluginDetails()->asTranslationArray();
		$outputHelper = $this->styles->getStyler( $input, $output );

		// Show details on screen.
		$outputHelper->table(
			array( 'Placeholder', 'Inputted Value' ),
			$this->pluginBuilder->pluginDetails()->asPlaceholderList()
		);

		// Verify user input.
		if ( ! $outputHelper->confirm( '<darkpink>Are your details corrent?</>' ) ) {
			$outputHelper->caution( 'USER ABORTED PROCESS' );
			exit;
		}

		// Clone repo & remove .git
		$this->cloneRepoToTempDirectory();

		// Replace all placehoders.
		if ( ! $this->populateFiles( $translations ) ) {
			$output->writeln( '<error>FAILED TO POPULATE MAIN FILES. PLEASE TRY AGAIN</>' );
			exit;
		}

		// Replace placeholders in composer.json
		if ( ! $this->populateComposer( $translations ) ) {
			$output->writeln( '<error>FAILED TO POPULATE COMPOSER.JSON, ABORTED OPERATION. PLEASE TRY AGAIN</>' );
			exit;
		}

		// Move files from temp to root.
		$output->writeln( '<lightpink>Moving files from temp to root directory' );
		$this->fileSystem->mirror(
			$this->settings->getTempPath(),
			$this->settings->getBasePath(),
			null,
			array( 'override' => true )
		);

		// Remove the empty temp directory.
		$output->writeln( '<lightpink>Cleaning up temp directory' );
		$this->fileSystem->remove( $this->settings->getTempPath() );

		// Check if created from pinkcrab bin
		if ( file_exists( $this->settings->getBasePath() . \DIRECTORY_SEPARATOR . 'pinkcrab' ) ) {
			$this->fileSystem->remove( $this->settings->getBasePath() . \DIRECTORY_SEPARATOR . 'pinkcrab' );
			$output->writeln( '<lightpink>Removed local builder binary' );
		}

		$output->writeln( '<darkpink>Plugin Built' );

		$output->writeln( '<lightpink>Don\'t forget to run composer build-dev rather than composer install!' );

	}


	/**
	 * Verfifies that none of the plugin details has errors.
	 * Returns error to cli if found.
	 *
	 * @param OutputInterface $output
	 * @return void
	 */
	protected function verifyPluginDetails( OutputInterface $output ): void {
		if ( $this->pluginBuilder->hasErrors() ) {
			$output->writeln( PHP_EOL );
			foreach ( $this->pluginBuilder->getErrors() as $error ) {
				$output->writeln( "<error>{$error}</>" );
			}
			$output->writeln( '<error>BUILD TERMINATED</>' );
			$output->writeln( PHP_EOL );
			exit;
		}
	}

	/**
	 * Clones the repo to the defined temp directory.
	 *
	 * @return void
	 */
	protected function cloneRepoToTempDirectory(): void {
		$this->gitRepository->clone(
			$this->settings->getRepoUri(),
			$this->settings->getTempPath(),
			$this->settings->getRepoBranch()
		)->removeGitConfig( $this->settings->getTempPath() . '/.git' );
	}

	/**
	 * Populates all the main files.
	 *
	 * @param array<string, string> $translations
	 * @return bool
	 */
	protected function populateFiles( array $translations ): bool {
		try {
			$this->filePopulator->translateFiles(
				$this->settings->getTempPath(),
				$translations,
				$this->settings->getExcludedDirectories(),
				$this->settings->getExcludedFiles()
			);
		} catch ( \Throwable $th ) {
			return false;
		}
		return true;
	}

	/**
	 * Populates composer.json
	 * uses double \\ on namespaces (well \\\\ with escaping)
	 *
	 * @param array<string, string> $translations
	 * @return bool
	 */
	protected function populateComposer( array $translations ): bool {
		$translations['##NAMESPACE##'] = Strings::doubleSlashes( $translations['##NAMESPACE##'] );
		return $this->filePopulator->findAndPopulateFile(
			'composer.json',
			$translations,
			$this->settings->getTempPath()
		);
	}
}
