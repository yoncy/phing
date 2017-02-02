<?php

namespace Phing\Tests\Type\Selector;

use Phing\Test\Helper\AbstractBuildFileTest;

/**
 * Class DifferentSelectorTest
 *
 * Test cases for different selectors.
 */
class DifferentSelectorTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        $this->configureProject(
            PHING_TEST_BASE . '/etc/types/selectors/DifferentSelectorTest.xml'
        );
        $this->executeTarget('setup');
    }

    public function tearDown()
    {
        $this->executeTarget('clean');
    }

    public function testSameTime()
    {
        $this->executeTarget(__FUNCTION__);
        $project = $this->getProject();
        $result = $project->getProperty('result');
        $this->assertFileNotExists($result . '/a.txt');
    }

    public function testDifferentTime()
    {
        $this->executeTarget(__FUNCTION__);
        $project = $this->getProject();
        $result = $project->getProperty('result');
        $this->assertFileExists($result . '/b.txt');
    }
}
