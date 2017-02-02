<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://phing.info>.
 */
namespace Phing\Condition;

use Phing\Exception\BuildException;
use Phing\Task\System\Available;
use IteratorAggregate;
use Phing\AbstractProjectComponent;
use Phing\Parser\CustomChildCreatorInterface;
use Phing\Project;
use Phing\Task\System\PhingVersion;


/**
 * Abstract baseclass for the <condition> task as well as several
 * conditions - ensures that the types of conditions inside the task
 * and the "container" conditions are in sync.
 *
 * @author  Hans Lellelid <hans@xmpl.org>
 * @author    Andreas Aderhold <andi@binarycloud.com>
 * @copyright 2001,2002 THYRELL. All rights reserved
 * @version   $Id$
 * @package   phing.tasks.system.condition
 */
abstract class AbstractCondition extends AbstractProjectComponent
    implements IteratorAggregate, CustomChildCreatorInterface
{
    public $conditions = []; // needs to be public for "inner" class access

    /** @var string $taskName */
    private $taskName = 'condition';

    public function __construct($taskName = 'component')
    {
        $this->taskName = $taskName;
    }

    /**
     * Sets the name to use in logging messages.
     *
     * @param string $name The name to use in logging messages.
     *                     Should not be <code>null</code>.
     */
    public function setTaskName($name)
    {
        $this->taskName = $name;
    }

    /**
     * Returns the name to use in logging messages.
     *
     * @return string the name to use in logging messages.
     */
    public function getTaskName()
    {
        return $this->taskName;
    }

    /**
     * @return int
     */
    public function countConditions()
    {
        return count($this->conditions);
    }

    /**
     * Required for IteratorAggregate
     */
    public function getIterator()
    {
        return new \Phing\Condition\ConditionEnumeration($this);
    }

    /**
     * @return ConditionInterface[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param Available $a
     * @return void
     */
    public function addAvailable(Available $a)
    {
        $this->conditions[] = $a;
    }

    /**
     * @return NotCondition
     */
    public function createNot()
    {
        $num = array_push($this->conditions, new NotCondition());

        return $this->conditions[$num - 1];
    }

    /**
     * @return AndCondition
     */
    public function createAnd()
    {
        $num = array_push($this->conditions, new AndCondition());

        return $this->conditions[$num - 1];
    }

    /**
     * @return OrCondition
     */
    public function createOr()
    {
        $num = array_push($this->conditions, new OrCondition());

        return $this->conditions[$num - 1];
    }

    /**
     * @return XorCondition
     */
    public function createXor()
    {
        $num = array_push($this->conditions, new XorCondition());

        return $this->conditions[$num - 1];
    }

    /**
     * @return Equals
     */
    public function createEquals()
    {
        $num = array_push($this->conditions, new Equals());

        return $this->conditions[$num - 1];
    }

    /**
     * @return OsCondition
     */
    public function createOs()
    {
        $num = array_push($this->conditions, new OsCondition());

        return $this->conditions[$num - 1];
    }

    /**
     * @return IsFalse
     */
    public function createIsFalse()
    {
        $num = array_push($this->conditions, new IsFalse());

        return $this->conditions[$num - 1];
    }

    /**
     * @return IsTrue
     */
    public function createIsTrue()
    {
        $num = array_push($this->conditions, new IsTrue());

        return $this->conditions[$num - 1];
    }

    /**
     * @return IsPropertyFalse
     */
    public function createIsPropertyFalse()
    {
        $num = array_push($this->conditions, new IsPropertyFalse());

        return $this->conditions[$num - 1];
    }

    /**
     * @return IsPropertyTrue
     */
    public function createIsPropertyTrue()
    {
        $num = array_push($this->conditions, new IsPropertyTrue());

        return $this->conditions[$num - 1];
    }

    /**
     * @return Contains
     */
    public function createContains()
    {
        $num = array_push($this->conditions, new Contains());

        return $this->conditions[$num - 1];
    }

    /**
     * @return IsSetCondition
     */
    public function createIsSet()
    {
        $num = array_push($this->conditions, new IsSetCondition());

        return $this->conditions[$num - 1];
    }

    /**
     * @return ReferenceExists
     */
    public function createReferenceExists()
    {
        $num = array_push($this->conditions, new ReferenceExists());

        return $this->conditions[$num - 1];
    }

    public function createVersionCompare()
    {
        $num = array_push($this->conditions, new VersionCompare());

        return $this->conditions[$num - 1];
    }

    public function createHttp()
    {
        $num = array_push($this->conditions, new Http());

        return $this->conditions[$num - 1];
    }

    public function createPhingVersion()
    {
        $num = array_push($this->conditions, new PhingVersion());

        return $this->conditions[$num - 1];
    }

    public function createHasFreeSpace()
    {
        $num = array_push($this->conditions, new HasFreeSpace());

        return $this->conditions[$num - 1];
    }

    public function createFilesMatch()
    {
        $num = array_push($this->conditions, new FilesMatch());

        return $this->conditions[$num - 1];
    }

    public function createSocket()
    {
        $num = array_push($this->conditions, new Socket());

        return $this->conditions[$num - 1];
    }

    public function createIsFailure()
    {
        $num = array_push($this->conditions, new IsFailure());

        return $this->conditions[$num - 1];
    }

    public function createIsFileSelected()
    {
        $num = array_push($this->conditions, new IsFileSelected());

        return $this->conditions[$num - 1];
    }

    public function createMatches()
    {
        $num = array_push($this->conditions, new Matches());

        return $this->conditions[$num - 1];
    }

    /**
     * @param  string         $elementName
     * @param  Project        $project
     * @throws BuildException
     * @return ConditionInterface
     */
    public function customChildCreator($elementName, Project $project)
    {
        $condition = $project->createCondition($elementName);
        $num = array_push($this->conditions, $condition);

        return $this->conditions[$num - 1];
    }
}
