<?php

/**
 * Selection of styles
 */

namespace PinkCrab\Plugin_Boilerplate_Builder\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class CommandStyles
{

    /**
     * @var array<string, OutputFormatterStyle>
     */
    protected array $styles = array();

    public function __construct()
    {
        $this->styles['darkpink']  = new OutputFormatterStyle('#ff5ca1', null, array( 'bold', 'blink' ));
        $this->styles['lightpink'] = new OutputFormatterStyle('#ebacc7', null, array());
    }

    /**
     * Used to register all the styles to an Output instatnce.
     *
     * @param OutputInterface $output
     * @return OutputInterface
     */
    public function registerStyles(OutputInterface $output): OutputInterface
    {
        foreach ($this->styles as $key => $style) {
            $output->getFormatter()->setStyle($key, $style);
        }
        return $output;
    }
}
