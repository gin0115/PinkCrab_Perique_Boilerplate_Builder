# PinkCrab Plugin Framework Builder

# Version 0.3.0
> Currently compatible with V0.3.* of the Plugin Boilerplate (which uses 0.3.* version of the core codebase)

This is a simple executeable which can be used to create an fully functional version of the PinkCrab Plugin Framework

To create a new plugin just copy the pinkcrab executeable from the bin to your empty plugin dir and run ```./pinkcrab```

You will then be guided through the setup process, where you will asked a number of questions to help setup the plugin. Once this has all been completed, the plugin should be created and the inital executeable will be removed.

This will clone in a full version of the plugin boilerplate, composer install has not been run yet, so feel free to add all additional depenedencies. Once you are happy and ready we can install our dependencies. Do not however run composer install!!

```bash
# RUN ME FOR composer install
$ ./build.sh --dev

# RUN ME FOR composer install --no-dev
$ ./build.sh
```

## PHP Scoper

The version of the boilerplate installed, comes set with (slightly clunky) config for using PHP Scoper. This allows you to create your plugin with a unique namespace, not only for your own code, but all the dependencies you are using. This removes the potential for conflicts with other plugins which could be using an older or newer version of a library.

When the custom build.sh script is run, ```composer install``` will be run, this will create our normal vendor directory and autloader. Then a patcher of all functions, classes and constants are taken for WP Core, WooCommerce and ACF (all latest versions). Then ```composer install --no-dev``` is run to give us a production build. PHP Scoper is then run, using the WP, WooCommerce and ACF patcher and a custom  vendor directory and autoloader is created (in build/vendor). Then we run ```composer install``` again, to give us back our intial vendor and autoloader.

### WHY 3 COMPOSER INSTALLS!!!!

Well, we could have skipped the last 2 composer installs and just created a custom vendor/autloader using the dev dependencies, but thats totally pointless as most depenecies are for testing. So after we run the first dev build, we use static anaylis stubs for our WP/WC/ACF **patchers** (all functions, classes and constants TO NOT apply a custom namespace to). So once we generate them, we run composer isntall for a dev build, scope that and then run again so we can use phpunit, php-stan and phpcs.

### So Which Copy

As we have 2 sets of vendor and autoloaders, you need be careful which you choose to use in your code. During the setup process it will have asked you for a the namespace prefix to use. Always choose the pefixed one in your production code (/src directory)

```php
use My_Custom_Prefix\PinkCrab\Core\...;
use My_Custom_Prefix\Guzzle\....;
use My_Custom_Prefix\Psr\....;
```

In all of your tests you can use the regular namespaces (this is why we have 2 vendor directories) for all of your testing dependencies. 

```php
use PHPUnit\Framework\TestCase;
use Silly\Command\Command;
use Symfony\Component\Finder\Finder;
```

## Development

To recompile the PHAR based on the current codebase, just run ```composer build-phar```