<?php

namespace Phing\Test\Task\Ext;

use Phing\Project;
use Phing\Task\Ext\NotifySend;
use Phing\Test\Helper\AbstractBuildFileTest;

class NotifySendTest extends AbstractBuildFileTest
{
    /**
     * @var NotifySend
     */
    protected $object;

    public function setUp()
    {
        $this->configureProject(PHING_TEST_BASE . "/etc/tasks/ext/NotifySendTaskTest.xml");
        $this->object = new NotifySend();
    }

    public function testEmptyMessage()
    {
        $this->executeTarget("testEmptyMessage");
        $this->assertInLogs('cmd: notify-send -i info Phing');
        $this->assertInLogs("Message: ''", Project::MSG_DEBUG);
    }

    public function testSettingTitle()
    {
        $this->object->setTitle("Test");
        $this->assertEquals("Test", $this->object->getTitle());
        $this->object->setTitle("Test Again");
        $this->assertEquals("Test Again", $this->object->getTitle());
    }

    public function testSettingMsg()
    {
        $this->object->setMsg("Test");
        $this->assertEquals("Test", $this->object->getMsg());
        $this->object->setMsg("Test Again");
        $this->assertEquals("Test Again", $this->object->getMsg());
    }

    public function testSetStandardIcon()
    {
        $this->object->setIcon("info");
        $this->assertEquals("info", $this->object->getIcon());

        $this->object->setIcon("error");
        $this->assertEquals("error", $this->object->getIcon());

        $this->object->setIcon("warning");
        $this->assertEquals("warning", $this->object->getIcon());
    }

    public function testSetNonStandardIcon()
    {
        $this->object->setIcon("informational");
        $this->assertEquals("info", $this->object->getIcon());
    }
}
