<?php

namespace Phing\Test\Io;

use Phing\Io\File;
use PHPUnit_Framework_TestCase;

class FileTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var File
     */
    private $file;

    protected function setUp()
    {
        $this->file = new File(__FILE__);
    }

    public function testPathInsideBasedir()
    {
        $this->assertEquals(basename(__FILE__), $this->file->getPathWithoutBase(__DIR__));
    }

    public function testPathOutsideBasedir()
    {
        $this->assertEquals(__FILE__, $this->file->getPathWithoutBase("/foo/bar"));
    }
}
