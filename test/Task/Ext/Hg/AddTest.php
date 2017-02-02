<?php
namespace Phing\Test\Task\Ext\Hg;

use Phing\Test\Helper\AbstractBuildFileTest;

class AddTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        mkdir(PHING_TEST_BASE . '/tmp/hgtest');
        $this->configureProject(
            PHING_TEST_BASE
            . '/etc/tasks/ext/hg/HgAddTaskTest.xml'
        );
    }

    public function tearDown()
    {
        HgTestsHelper::rmdir(PHING_TEST_BASE . "/tmp/hgtest");
    }

    public function testWrongRepository()
    {
        $this->expectBuildExceptionContaining(
            'wrongRepository',
            'is not a directory',
            "does not exist"
        );
    }
}
