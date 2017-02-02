<?php
namespace Phing\Task\Ext\Hg;

use Phing\Test\Helper\AbstractBuildFileTest;
use Phing\Test\Task\Ext\Hg\HgTestsHelper;

class HgArchiveTaskTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        mkdir(PHING_TEST_BASE . '/tmp/hgtest');
        $this->configureProject(
            PHING_TEST_BASE
            . '/etc/tasks/ext/hg/HgArchiveTaskTest.xml'
        );
    }

    public function tearDown()
    {
        HgTestsHelper::rmdir(PHING_TEST_BASE . "/tmp/hgtest");
    }

    public function testDestinationNotSpecified()
    {
        $this->expectBuildExceptionContaining(
            'destinationNotSpecified',
            "destinationNotSpecified",
            "Destination must be set."
        );
    }
}
