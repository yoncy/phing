<?php

namespace Phing\Type\CommandLine;

use Phing\Io\File;
use Phing\Type\CommandLine;

/**
 * "Inner" class used for nested xml command line definitions.
 *
 * @package phing.types
 */
class CommandLineArgument
{
    private $parts = [];
    private $outer;
    public $escape = false;

    /**
     * @param CommandLine $outer
     */
    public function __construct(CommandLine $outer)
    {
        $this->outer = $outer;
    }

    /**
     * @param bool $escape
     */
    public function setEscape($escape)
    {
        $this->escape = $escape;
    }

    /**
     * Sets a single commandline argument.
     *
     * @param string $value a single commandline argument.
     */
    public function setValue($value)
    {
        $this->parts = array($value);
    }

    /**
     * Line to split into several commandline arguments.
     *
     * @param string $line line to split into several commandline arguments
     */
    public function setLine($line)
    {
        if ($line === null) {
            return;
        }
        $this->parts = $this->outer->translateCommandline($line);
    }

    /**
     * Sets a single commandline argument and treats it like a
     * PATH - ensures the right separator for the local platform
     * is used.
     *
     * @param mixed $value a single commandline argument.
     */
    public function setPath($value)
    {
        $this->parts = array((string)$value);
    }

    /**
     * Sets a single commandline argument to the absolute filename
     * of the given file.
     *
     * @param a|File $value
     * @internal param a $value single commandline argument.
     */
    public function setFile(File $value)
    {
        $this->parts = array($value->getAbsolutePath());
    }

    /**
     * Returns the parts this Argument consists of.
     * @return array string[]
     */
    public function getParts()
    {
        return $this->parts;
    }
}