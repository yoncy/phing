<?php
namespace Phing\Test\Task\Ext\Hg;

use Phing\Test\Helper\AbstractBuildFileTest;

class UpdateTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        mkdir(PHING_TEST_BASE . '/tmp/hgtest');
        $this->configureProject(
            PHING_TEST_BASE
            . '/etc/tasks/ext/hg/HgUpdateTaskTest.xml'
        );
    }

    public function tearDown()
    {
        HgTestsHelper::rmdir(PHING_TEST_BASE . "/tmp/hgtest");
    }

    public function testWrongRepositoryDirDoesntExist()
    {
        $this->expectBuildExceptionContaining(
            'wrongRepositoryDirDoesntExist',
            'repository directory does not exist',
            "Repository directory 'inconcievable-buttercup' does not exist."
        );
    }

    public function testWrongRepository()
    {
        $this->expectBuildExceptionContaining(
            'wrongRepository',
            'wrong repository',
            "abort"
        );
        $this->assertInLogs("Executing: hg update");
    }
}
