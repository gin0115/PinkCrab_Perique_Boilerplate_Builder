<?php

/**
 * String helper functions
 *
 * @package PinkCrab Plugin Builder\Git
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @since 0.2.0
 */

declare(strict_types=1);

namespace PinkCrab\Plugin_Boilerplate_Builder\Helper;

class Strings
{
    /**
     * Replaces all \ with \\ in the passed string.
     * Due to escaping, is actually \\ to \\\\
     *
     * @since 0.2.0
     * @param string $string
     * @return string
     */
    public static function doubleSlashes(string $string): string
    {
        return \str_replace('\\', '\\\\', $string);
    }
}
