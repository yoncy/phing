<?php

namespace Phing\Test\Task\Ext\Hg;

use Phing\Test\Helper\AbstractBuildFileTest;

class InitTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        mkdir(PHING_TEST_BASE . '/tmp/hgtest');
        $this->configureProject(
            PHING_TEST_BASE
            . '/etc/tasks/ext/hg/HgInitTaskTest.xml'
        );
    }

    public function tearDown()
    {
        HgTestsHelper::rmdir(PHING_TEST_BASE . "/tmp/hgtest");
    }

    public function testHgInit()
    {
        $repository = PHING_TEST_BASE . '/tmp/hgtest';
        $HGdir = $repository . '/.hg';
        $this->executeTarget('hgInit');
        $this->assertInLogs('Initializing');
        $this->assertTrue(is_dir($repository));
        $this->assertTrue(is_dir($HGdir));
    }

    public function testWrongRepository()
    {
        $this->expectBuildExceptionContaining('wrongRepository', 'is not a directory', "is not a directory");
    }
}
