<?php

namespace Phing\Test\Condition;

use Phing\Test\Helper\AbstractBuildFileTest;

/**
 * Tests the IsPropertyFalse Condition
 *
 * @author  Siad Ardroumli <siad.ardroumli@gmail.com>
 * @package phing.tasks.system
 */
class IsPropertyFalseTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        $this->configureProject(
            PHING_TEST_BASE . '/etc/tasks/system/IsPropertyTrueFalseTest.xml'
        );
    }

    public function testIsPropertyFalse()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertySet('IsFalse');
    }

    public function testIsPropertyNotFalse()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertyUnset('IsNotFalse');
    }
}
