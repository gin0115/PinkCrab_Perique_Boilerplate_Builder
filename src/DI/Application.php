<?php

/**
 * Application container.
 */

namespace PinkCrab\Plugin_Boilerplate_Builde\DI;

use DI\Container;
use DI\ContainerBuilder;
use Silly\Edition\PhpDi\Application;

class MyApplication extends Application {

	protected function createContainer() {
		$builder = ContainerBuilder::buildDevContainer();

		$builder->addDefinitions(
			array(
				Application::class => $this,
			)
		);

		return $builder->build(); // return the customized container
	}
}
