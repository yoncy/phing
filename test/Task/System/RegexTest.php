<?php
namespace Phing\Test\Task\System;

use Phing\Test\Helper\AbstractBuildFileTest;

/**
 * Tests the PropertyRegexTask Task
 *
 * @author  SiadArdroumli <siad.ardroumli@gmail.com>
 * @package phing.tasks.ext.property
 */
class RegexTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        $this->configureProject(
            PHING_TEST_BASE . '/etc/tasks/system/RegExTaskTest.xml'
        );
    }

    public function testPropertyRegex()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertyEquals('test.name', 'ABC');
    }

    public function testPropertyRegexReplace()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertyEquals('test.name', 'test.DEF.name');
    }

    public function testBackslash()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertyEquals('pack.name', '123');
    }
}
