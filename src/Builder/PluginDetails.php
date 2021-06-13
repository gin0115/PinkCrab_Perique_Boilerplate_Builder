<?php

/**
 * Holds the settings for a plugin.
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Builder;

use Exception;
use Reflection;
use ReflectionClass;
use ReflectionProperty;
use PinkCrab\Plugin_Boilerplate_Builder\Builder\PluginSetting;

class PluginDetails
{
    /**
     * @var PluginSetting
     */
    protected $pluginName;
    /**
     * @var PluginSetting
     */
    protected $pluginUrl;
    /**
     * @var PluginSetting
     */
    protected $pluginDescription;
    /**
     * @var PluginSetting
     */
    protected $pluginTextdomain;
    /**
     * @var PluginSetting
     */
    protected $pluginVersion;
    /**
     * @var PluginSetting
     */
    protected $authorName;
    /**
     * @var PluginSetting
     */
    protected $authorEmail;
    /**
     * @var PluginSetting
     */
    protected $authorUrl;
    /**
     * @var PluginSetting
     */
    protected $primaryNamespace;
    /**
     * @var PluginSetting
     */
    protected $scoperPrefix;
    /**
     * @var PluginSetting
     */
    protected $composerName;
    /**
     * @var PluginSetting
     */
    protected $autoloadDevPrefix;

    /**
     * Returns all getters as an array.
     *
     * @return array<string, PluginSetting>
     */
    public function toArray(): array
    {
        $array = array();
        $refection = new ReflectionClass($this);
        $properties = $refection->getProperties(ReflectionProperty::IS_PROTECTED);

        // Itterate through, and compile array of properties.
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this);
        }
        return $array;
    }

    /**
     * Returns the settings as an array wich can be used to
     * translate from placeholders.
     *
     * @param callable|null $filter
     * @return array<string, string>
     */
    public function asTranslationArray(?callable $filter = null): array
    {
        return array_reduce(
            $this->toArray(),
            function (array $carry, PluginSetting $setting) use ($filter): array {
                $carry[$setting->getPlaceholder()] = $filter
                    ? $filter($setting->getResponse(), $setting)
                    : $setting->getResponse();
                return $carry;
            },
            []
        );
    }

    /**
     * Returns the placeholder and replacements as a simple list
     * [
     *  ['placeholder_a' , 'replacement_a'],
     *  ['placeholder_b' , 'replacement_b'],
     *  ....
     * ]
     *
     * @return array<string, array<int, string>>
     */
    public function asPlaceholderList(): array
    {
        return array_map(
            fn(PluginSetting $setting): array =>  [$setting->getPlaceholder(), $setting->getResponse()],
            $this->toArray()
        );
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

    /**
     * Set the value of pluginName
     *
     * @param PluginSetting $pluginName
     * @return self
     */
    public function setPluginName(PluginSetting $pluginName): self
    {
        $this->pluginName = $pluginName;
        return $this;
    }

    /**
     * Set the value of pluginUrl
     *
     * @param PluginSetting $pluginUrl
     * @return self
     */
    public function setPluginUrl(PluginSetting $pluginUrl): self
    {
        $this->pluginUrl = $pluginUrl;
        return $this;
    }

    /**
     * Set the value of pluginDescription
     *
     * @param PluginSetting $pluginDescription
     * @return self
     */
    public function setPluginDescription(PluginSetting $pluginDescription): self
    {
        $this->pluginDescription = $pluginDescription;
        return $this;
    }

    /**
     * Set the value of pluginTextdomain
     *
     * @param PluginSetting $pluginTextdomain
     * @return self
     */
    public function setPluginTextdomain(PluginSetting $pluginTextdomain): self
    {
        $this->pluginTextdomain = $pluginTextdomain;
        return $this;
    }

    /**
     * Set the value of pluginVersion
     *
     * @param PluginSetting $pluginVersion
     * @return self
     */
    public function setPluginVersion(PluginSetting $pluginVersion): self
    {
        $this->pluginVersion = $pluginVersion;
        return $this;
    }

    /**
     * Set the value of authorName
     *
     * @param PluginSetting $authorName
     * @return self
     */
    public function setAuthorName(PluginSetting $authorName): self
    {
        $this->authorName = $authorName;
        return $this;
    }

    /**
     * Set the value of authorEmail
     *
     * @param PluginSetting $authorEmail
     * @return self
     */
    public function setAuthorEmail(PluginSetting $authorEmail): self
    {
        $this->authorEmail = $authorEmail;
        return $this;
    }

    /**
     * Set the value of authorUrl
     *
     * @param PluginSetting $authorUrl
     * @return self
     */
    public function setAuthorUrl(PluginSetting $authorUrl): self
    {
        $this->authorUrl = $authorUrl;
        return $this;
    }

    /**
     * Set the value of primaryNamespace
     *
     * @param PluginSetting $primaryNamespace
     * @return self
     */
    public function setPrimaryNamespace(PluginSetting $primaryNamespace): self
    {
        $this->primaryNamespace = $primaryNamespace;
        return $this;
    }

    /**
     * Set the value of scoperPrefix
     *
     * @param PluginSetting $scoperPrefix
     * @return self
     */
    public function setScoperPrefix(PluginSetting $scoperPrefix): self
    {
        $this->scoperPrefix = $scoperPrefix;
        return $this;
    }

    /**
     * Set the value of composerName
     *
     * @param PluginSetting $composerName
     * @return self
     */
    public function setComposerName(PluginSetting $composerName): self
    {
        $this->composerName = $composerName;
        return $this;
    }

    /**
     * Set the value of autoloadDevPrefix
     *
     * @param PluginSetting $autoloadDevPrefix
     * @return self
     */
    public function setAutoloadDevPrefix(PluginSetting $autoloadDevPrefix): self
    {
        $this->autoloadDevPrefix = $autoloadDevPrefix;
        return $this;
    }
}
