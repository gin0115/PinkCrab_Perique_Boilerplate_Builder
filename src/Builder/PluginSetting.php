<?php

/**
 * Holds a settings value and basic validation/formatting.
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Builder;

class PluginSetting
{
    /**
     * @var string
     */
    protected string $handle = '';
    /**
     * @var string
     */
    protected string $question = '';
    /**
     * @var string
     */
    protected string $subLine = '';
    /**
     * @var bool
     */
    protected bool $required = false;
    /**
     * @var string
     */
    protected string $placeholder = '';
    /**
     * @var array<string, ?callable>
     */
    protected array $sanitization = ['validation' => null,'formatting' => null];
    /**
     * @var string
     */
    protected string $response = '';
    /**
     * @var string
     */
    protected string $error = '';

    public function __construct(string $handle)
    {
        $this->handle = $handle;
    }

    /**
     * Sets a response
     *
     * @param string $response
     * @return self
     */
    public function withResponse(string $response): self
    {
        $cloned = clone $this;
        $cloned->setResponse($response);
        return $cloned;
    }

    /**
     * Sets the response.
     *
     * @param string $response
     * @return self
     */
    protected function setResponse(string $response): self
    {

        if (
            is_callable($this->sanitization['validation'])
            && ! $this->sanitization['validation']($response)
        ) {
            $this->error(
                sprintf(
                    'Failed to set %s\'s response as failed validation. \'%s\' was passed.',
                    $this->handle,
                    $response
                )
            );
            return $this;
        }

        $this->response = is_callable($this->sanitization['formatting'])
            ? $this->sanitization['formatting']($response)
            : $response;

        return $this;
    }

     /**
     * Get the value of response
     *
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * Get the value of question
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * Set the value of question
     *
     * @return self
     */
    public function question(string $question): self
    {
        $this->question = $question;
        return $this;
    }

    public function hasSubline(): bool
    {
        return \strlen($this->getSubLine()) !== 0;
    }

    /**
     * Get the value of subLine
     */
    public function getSubLine(): string
    {
        return $this->subLine;
    }

    /**
     * Set the value of subLine
     *
     * @return self
     */
    public function subLine(string $subLine): self
    {
        $this->subLine = $subLine;
        return $this;
    }

    /**
     * Get the value of required
     */
    public function getRequired(): bool
    {
        return $this->required;
    }

    /**
     * Set the value of required
     *
     * @param bool $required
     * @return self
     */
    public function required(bool $required = true): self
    {
        $this->required = $required;
        return $this;
    }

    /**
     * Get the value of placeholder
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * Set the value of placeholder
     *
     * @return self
     */
    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Set the value of validation
     *
     * @param callable $validation
     * @return self
     */
    public function validation(callable $validation): self
    {
        $this->sanitization['validation'] = $validation;
        return $this;
    }

    /**
     * Set the value of formatting
     *
     * @param callable $formatting
     * @return self
     */
    public function formatting(callable $formatting): self
    {
        $this->sanitization['formatting'] = $formatting;
        return $this;
    }

    /**
     * Get the value of handle
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * Checks if error has been triggered.
     */
    public function hasError(): bool
    {
        return \strlen($this->error) !== 0;
    }

    /**
     * Get the value of error
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * Set the value of error
     *
     * @return self
     */
    public function error(string $error): self
    {
        $this->error = $error;
        return $this;
    }
}
