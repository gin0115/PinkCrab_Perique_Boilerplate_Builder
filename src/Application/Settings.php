<?php

/**
 * Settings object.
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Application;

class Settings
{
    /**
     * @var null|string
     */
    protected ?string $appName = null;
    /**
     * @var null|string
     */
    protected ?string $appVersion = null;
    /**
     * @var null|string
     */
    protected ?string $basePath = null;
    /**
     * @var null|string
     */
    protected ?string $tempPath = null;
    /**
     * @var null|string
     */
    protected ?string $repoUri = null;
    /**
     * @var null|string
     */
    protected ?string $repoBranch = null;
    /**
     * @var array<int, string>
     */
    protected array $excludedFiles = [];
    /**
     * @var array<int, string>
     */
    protected array $excludedDirectories = [];

    /**
     * Get the value of basePath
     */
    public function getBasePath(): string
    {
        return $this->basePath ?? \dirname(__DIR__, 3);
    }

    /**
     * Set the value of basePath
     *
     * @return self
     */
    public function setBasePath(string $basePath): self
    {
        $this->basePath = $basePath;
        return $this;
    }

    /**
     * Get the value of tempPath
     */
    public function getTempPath(): string
    {
        return $this->tempPath ?? \dirname(__DIR__, 3) . '/tmp';
    }

    /**
     * Set the value of tempPath
     *
     * @return self
     */
    public function setTempPath(string $tempPath): self
    {
        $this->tempPath = $tempPath;
        return $this;
    }

    /**
     * Get the value of repoUri
     */
    public function getRepoUri(): string
    {
        return $this->repoUri ?? '';
    }

    /**
     * Set the value of repoUri
     *
     * @return self
     */
    public function setRepoUri(string $repoUri): self
    {
        $this->repoUri = $repoUri;
        return $this;
    }

    /**
     * Get the value of repoBranch
     */
    public function getRepoBranch(): string
    {
        return $this->repoBranch ?? 'master';
    }

    /**
     * Set the value of repoBranch
     *
     * @return self
     */
    public function setRepoBranch(string $repoBranch): self
    {
        $this->repoBranch = $repoBranch;
        return $this;
    }

    /**
     * Get the value of excludedFiles
     * @return string[]
     */
    public function getExcludedFiles(): array
    {
        return $this->excludedFiles;
    }

    /**
     * Set the value of excludedFiles
     * @param string[] $excludedFiles
     * @return self
     */
    public function setExcludedFiles(array $excludedFiles): self
    {
        $this->excludedFiles = $excludedFiles;
        return $this;
    }

    /**
     * Get the value of excludedDirectories
     * @return string[]
     */
    public function getExcludedDirectories(): array
    {
        return $this->excludedDirectories;
    }

    /**
     * Set the value of excludedDirectories
     * @param string[] $excludedDirectories
     * @return self
     */
    public function setExcludedDirectories(array $excludedDirectories): self
    {
        $this->excludedDirectories = $excludedDirectories;
        return $this;
    }

    /**
     * Get the value of appName
     *
     * @return string
     */
    public function getAppName(): string
    {
        return $this->appName ?? 'UNKNOWN';
    }

    /**
     * Set the value of appName
     *
     * @param string $appName
     * @return self
     */
    public function setAppName(string $appName): self
    {
        $this->appName = $appName;
        return $this;
    }

    /**
     * Get the value of appVersion
     *
     * @return string
     */
    public function getAppVersion(): string
    {
        return $this->appVersion ?? 'UNKNOWN';
    }

    /**
     * Set the value of appVersion
     *
     * @param string $appVersion
     * @return self
     */
    public function setAppVersion(string $appVersion): self
    {
        $this->appVersion = $appVersion;
        return $this;
    }
}
