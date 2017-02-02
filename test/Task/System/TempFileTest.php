<?php

namespace Phing\Test\Task\System;

use Phing\Test\Helper\AbstractBuildFileTest;

/**
 * Tests the TempFile Task
 *
 * @author  Siad Ardroumli <siad.ardroumli@gmail.com>
 * @package phing.tasks.system
 */
class TempFileTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        $this->configureProject(
            PHING_TEST_BASE . '/etc/tasks/system/TempFileTest.xml'
        );
    }

    public function testTempFile()
    {
        $this->executeTarget(__FUNCTION__);
        self::assertStringStartsWith(
            $this->getProject()->getProperty('php.tmpdir'),
            $this->getProject()->getProperty('test.temp')
        );
    }
}
