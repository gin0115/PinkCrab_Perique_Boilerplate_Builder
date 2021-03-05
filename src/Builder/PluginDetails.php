<?php

/**
 * Holds the settings for a plugin.
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Builder;

use Exception;
use ReflectionClass;
use ReflectionProperty;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginSetting;

class PluginDetails
{
    /**
     * @var PluginSetting
     */
    protected PluginSetting $pluginName;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $pluginUrl;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $pluginDescription;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $pluginTextdomain;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $pluginVersion;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $authorName;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $authorEmail;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $authorUrl;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $primaryNamespace;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $WPNamespace;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $scoperPrefix;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $composerName;
    /**
     * @var PluginSetting
     */
    protected PluginSetting $autoloadDevPrefix;

    public function __construct()
    {

        /** PLUGIN DETAILS */
        $this->pluginName = ( new PluginSetting('plugin_name') )
            ->question('Please enter the plugin name')
            ->placeholder('##PLUGIN_NAME##')
            ->formatting('ucwords')
            ->required();

        $this->pluginUrl = ( new PluginSetting('plugin_url') )
            ->question('What is your plugins url')
            ->subLine('Must be entered as full and valid url https://www.url.tld')
            ->placeholder('##PLUGIN_URL##')
            ->formatting('strtolower')
            ->validation(fn($e): bool => (bool) filter_var($e, FILTER_VALIDATE_URL));

        $this->pluginDescription = ( new PluginSetting('plugin_description') )
            ->question('Your plugin description')
            ->subLine('This is used for both plugin.php and composer.json')
            ->placeholder('##PLUGIN_DESCRIPTION##');

        $this->pluginVersion = ( new PluginSetting('plugin_version') )
            ->question('Your plugin version')
            ->placeholder('##PLUGIN_VERSION##');

        $this->pluginTextdomain = ( new PluginSetting('plugin_textdomain') )
            ->question('Your plugin textdomain')
            ->placeholder('##PLUGIN_TEXTDOMAIN##');

        /** AUTHOR DETAILS */
        $this->authorName = ( new PluginSetting('author_name') )
            ->question('The primary author name')
            ->subLine('This is required, so please enter a valid name. You can remove after setup.')
            ->placeholder('##AUTHOR_NAME##')
            ->formatting('ucwords')
            ->required();

        $this->authorEmail = ( new PluginSetting('author_email') )
            ->question('The primary author email')
            ->subLine('This is required, so please enter a valid name. You can remove after setup.')
            ->placeholder('##AUTHOR_EMAIL##')
            ->formatting('strtolower')
            ->validation(fn($e): bool => (bool) filter_var($e, FILTER_VALIDATE_EMAIL))
            ->required();

        $this->authorUrl = ( new PluginSetting('author_url') )
            ->question('The primary author url')
            ->placeholder('##AUTHOR_URL##')
            ->formatting('strtolower')
            ->validation(fn($e): bool => (bool) filter_var($e, FILTER_VALIDATE_URL));

        /** COMPOSER DETAILS */
        $this->primaryNamespace = ( new PluginSetting('primary_namespace') )
            ->question('The primary namespace for all code in src directory')
            ->placeholder('##NAMESPACE##')
            ->subLine('Like Achme\\Plugin_Namespace')
            ->validation(
                function ($e): bool {
                    $regex = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff\\\\]*[a-zA-Z0-9_\x7f-\xff]$/';
                    return (bool) \preg_match_all($regex, $e);
                }
            );

        $this->WPNamespace = ( new PluginSetting('wp_namespace') )
            ->question('The namespace used for plugin activation and deactivation hooks in wp directory')
            ->placeholder('##NAMESPACE_WP##')
            ->subLine('should differ form primary namespace. Like Achme\\WP\\Plugin_Namespace')
            ->validation(
                function ($e): bool {
                    $regex = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff\\\\]*[a-zA-Z0-9_\x7f-\xff]$/';
                    return (bool) \preg_match_all($regex, $e);
                }
            );

        $this->scoperPrefix = ( new PluginSetting('scoper_prefix') )
            ->question('The unique PHP_Scoper prefix')
            ->placeholder('##SCOPER_PREFIX##')
            ->subLine('this must be as unique as possilble. Like Achme_Plugins_Project_XX123')
            ->validation(
                function ($e): bool {
                    $regex = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';
                    return (bool) \preg_match_all($regex, $e);
                }
            );

        $this->composerName = ( new PluginSetting('composer_name') )
            ->question('Please enter a valid composer project name')
            ->placeholder('##PACKAGE_NAME##')
            ->subLine('Like achme/plugin-for-things')
            ->validation(
                function ($e): bool {
                    $regex = '/^^[a-zA-Z\d\-\/]+$/';
                    return (bool) \preg_match_all($regex, $e);
                }
            );

        $this->autoloadDevPrefix = ( new PluginSetting('composer_dev_autoloader') )
            ->question('Please enter the prefix to apply to the dev autoloader')
            ->placeholder('##DEV_AUTLOADER_PREFIX##')
            ->subLine('must be valid chars for phpclass name. Like achme_plugin_x_dev')
            ->validation(
                function ($e): bool {
                    $regex = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';
                    return (bool) \preg_match_all($regex, $e);
                }
            );
    }

    /**
     * Returns all getters as an array.
     *
     * @return void
     */
    public function toArray()
    {
        $array = array();
        foreach ($this as $key => $value) {
            $array[ $key ] = $value;
        }
        return $array;
    }

    /**
     * Sets the response of a setting.
     *
     * @param string $setting
     * @param string $response
     * @return void
     * @throws Exception
     */
    public function setResponse(string $setting, string $response)
    {
        if (! \property_exists($this, $setting)) {
            throw new Exception("{$setting} doesnt exist in " . __CLASS__);
        }

        $this->{$setting} = $this->{$setting}->withResponse($response);
    }

    /**
     * Get the value of primaryNamespace
     * @return PluginSetting
     */
    public function getPrimaryNamespace(): PluginSetting
    {
        return $this->primaryNamespace;
    }

    /**
     * Get the value of pluginName
     * @return PluginSetting
     */
    public function getPluginName(): PluginSetting
    {
        return $this->pluginName;
    }

    /**
     * Get the value of pluginUrl
     * @return PluginSetting
     */
    public function getPluginUrl(): PluginSetting
    {
        return $this->pluginUrl;
    }

    /**
     * Get the value of pluginDescription
     * @return PluginSetting
     */
    public function getPluginDescription(): PluginSetting
    {
        return $this->pluginDescription;
    }

    /**
     * Get the value of pluginTextdomain
     * @return PluginSetting
     */
    public function getPluginTextdomain(): PluginSetting
    {
        return $this->pluginTextdomain;
    }

    /**
     * Get the value of pluginVersion
     * @return PluginSetting
     */
    public function getPluginVersion(): PluginSetting
    {
        return $this->pluginVersion;
    }

    /**
     * Get the value of authorName
     * @return PluginSetting
     */
    public function getAuthorName(): PluginSetting
    {
        return $this->authorName;
    }

    /**
     * Get the value of authorEmail
     * @return PluginSetting
     */
    public function getAuthorEmail(): PluginSetting
    {
        return $this->authorEmail;
    }

    /**
     * Get the value of authorUrl
     * @return PluginSetting
     */
    public function getAuthorUrl(): PluginSetting
    {
        return $this->authorUrl;
    }

    /**
     * Get the value of WPNamespace
     * @return PluginSetting
     */
    public function getWPNamespace(): PluginSetting
    {
        return $this->WPNamespace;
    }

    /**
     * Get the value of scoperPrefix
     * @return PluginSetting
     */
    public function getScoperPrefix(): PluginSetting
    {
        return $this->scoperPrefix;
    }

    /**
     * Get the value of composerName
     * @return PluginSetting
     */
    public function getComposerName(): PluginSetting
    {
        return $this->composerName;
    }

    /**
     * Get the value of autoloadDevPrefix
     * @return PluginSetting
     */
    public function getAutoloadDevPrefix(): PluginSetting
    {
        return $this->autoloadDevPrefix;
    }
}
