<?php
require_once 'phing/BuildFileTest.php';
require_once '../classes/phing/tasks/ext/hg/HgCloneTask.php';

class HgCloneTaskTest extends BuildFileTest
{
    public function setUp()
    {
        if (version_compare(PHP_VERSION, '5.4') < 0) {
            $this->markTestSkipped("Need PHP 5.4+ for this test");
        }
        $this->configureProject(
            PHING_TEST_BASE
            . '/etc/tasks/ext/hg/HgCloneTaskTest.xml'
        );
    }

    public function testWrongRepository()
    {
        $this->expectBuildExceptionContaining(
            'wrongRepository',
            'wrong repository',
            'abort'
        );
    }

    public function testNoRepositorySpecified()
    {
        $this->expectBuildExceptionContaining(
            'noRepository',
            'repository is not specified',
            '"repository" is a required parameter'
        );
    }

    public function testNoTargetPathSpecified()
    {
        $this->expectBuildExceptionContaining(
            'noTargetPath',
            'targetPath is not specified',
            '"targetPath" is a required parameter'
        );
    }
}

