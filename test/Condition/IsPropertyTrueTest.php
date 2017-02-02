<?php

namespace Phing\Test\Condition;

use Phing\Test\Helper\AbstractBuildFileTest;

/**
 * Tests the IsPropertyTrue/-False Tasks
 *
 * @author  Siad Ardroumli <siad.ardroumli@gmail.com>
 * @package phing.tasks.system
 */
class IsPropertyTrueTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        $this->configureProject(
            PHING_TEST_BASE . '/etc/tasks/system/IsPropertyTrueFalseTest.xml'
        );
    }

    public function testIsPropertyTrue()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertySet('IsTrue');
    }

    public function testIsPropertyNotTrue()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertyUnset('IsNotTrue');
    }
}
