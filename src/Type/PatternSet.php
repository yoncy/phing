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

namespace Phing\Type;

use Phing\Type\DataType;
use Phing\Exception\BuildException;
use Phing\Io\BufferedReader;
use Phing\Io\File;
use Phing\Io\FileReader;
use Phing\Io\IOException;
use Phing\Project;
use Phing\Type\Reference;

/**
 * The patternset storage component. Carries all necessary data and methods
 * for the patternset stuff.
 *
 * @author   Andreas Aderhold, andi@binarycloud.com
 * @version  $Id$
 * @package  phing.types
 */
class PatternSet extends DataType
{
    private $includeList = [];
    private $excludeList = [];
    private $creators = [];

    /**
     * Makes this instance in effect a reference to another PatternSet
     * instance.
     * You must not set another attribute or nest elements inside
     * this element if you make it a reference.
     * @param Reference $r
     * @throws \Phing\Exception\BuildException
     */
    public function setRefid(Reference $r)
    {
        if (!empty($this->includeList) || !empty($this->excludeList)) {
            throw $this->tooManyAttributes();
        }
        parent::setRefid($r);
    }

    /**
     * Add a name entry on the include list
     *
     * @return \Phing\Type\PatternSet\PatternSetNameEntry Reference to object
     * @throws \Phing\Exception\BuildException
     */
    public function createInclude()
    {
        if ($this->isReference()) {
            throw $this->noChildrenAllowed();
        }

        return $this->creatorValue($this->includeList);
    }

    /**
     * Add a name entry on the include files list
     *
     * @return \Phing\Type\PatternSet\PatternSetNameEntry Reference to object
     * @throws BuildException
     */
    public function createIncludesFile()
    {
        if ($this->isReference()) {
            throw $this->noChildrenAllowed();
        }

        return $this->creatorFile($this->includeList);
    }

    /**
     * Add a name entry on the exclude list
     *
     * @return \Phing\Type\PatternSet\PatternSetNameEntry Reference to object
     * @throws \Phing\Exception\BuildException
     */
    public function createExclude()
    {
        if ($this->isReference()) {
            throw $this->noChildrenAllowed();
        }

        return $this->creatorValue($this->excludeList);
    }

    /**
     * add a name entry on the exclude files list
     *
     * @return \Phing\Type\PatternSet\PatternSetNameEntry Reference to object
     * @throws \Phing\Exception\BuildException
     */
    public function createExcludesFile()
    {
        if ($this->isReference()) {
            throw $this->noChildrenAllowed();
        }

        return $this->creatorFile($this->excludeList);
    }

    /**
     * Sets the set of include patterns. Patterns may be separated by a comma
     * or a space.
     *
     * @param  string $includes the string containing the include patterns
     * @return void
     * @throws \Phing\Exception\BuildException
     */
    public function setIncludes($includes)
    {
        if ($this->isReference()) {
            throw $this->tooManyAttributes();
        }
        /*return*/
        $this->createInclude()->setName($includes);
    }

    /**
     * Sets the set of exclude patterns. Patterns may be separated by a comma
     * or a space.
     *
     * @param  string the string containing the exclude patterns
     * @return void
     * @throws \Phing\Exception\BuildException
     */
    public function setExcludes($excludes)
    {
        if ($this->isReference()) {
            throw $this->tooManyAttributes();
        }
        /*return*/
        $this->createExclude()->setName($excludes);
    }

    /**
     * add a name entry to the given list
     *
     * @param  array List onto which the nameentry should be added
     * @return \Phing\Type\PatternSet\PatternSetNameEntry Reference to the created PatternSetNameEntry instance
     */
    private function creatorValue(&$list)
    {
        $c = new PatternSet\PatternSetNameEntryValueCreator($list);
        $this->creators[] = $c;
        return $c;
    }

    private function creatorFile(&$list)
    {
        $c = new PatternSet\PatternSetNameEntryFileCreator($list, $this->project);
        $this->creators[] = $c;
        return $c;
    }

    /**
     * Sets the name of the file containing the includes patterns.
     *
     * @param File|File $includesFile file to fetch the include patterns from.
     *
     * @throws \Phing\Exception\BuildException
     */
    public function setIncludesFile($file)
    {
        if ($this->isReference()) {
            throw $this->tooManyAttributes();
        }

        $this->createIncludesFile()->setName($file);
    }

    /**
     * Sets the name of the file containing the excludes patterns.
     *
     * @param File $excludesFile file to fetch the exclude patterns from.
     * @throws BuildException
     */
    public function setExcludesFile($excludesFile)
    {
        if ($this->isReference()) {
            throw $this->tooManyAttributes();
        }
        if ($excludesFile instanceof File) {
            $excludesFile = $excludesFile->getPath();
        }
        $o = $this->createExcludesFile();
        $o->setName($excludesFile);
    }

    /**
     * Reads path matching patterns from a file and adds them to the
     * includes or excludes list
     *
     * @param File $patternfile
     * @param $patternlist
     * @param Project $p
     *
     * @throws BuildException
     */
    private function readPatterns(File $patternfile, &$patternlist, Project $p)
    {
        $patternReader = null;
        try {
            // Get a FileReader
            $patternReader = new BufferedReader(new FileReader($patternfile));

            // Create one NameEntry in the appropriate pattern list for each
            // line in the file.
            $line = $patternReader->readLine();
            while ($line !== null) {
                if (!empty($line)) {
                    $line = $p->replaceProperties($line);
                    $this->addPatternToList($patternlist)->setName($line);
                }
                $line = $patternReader->readLine();
            }
        } catch (IOException $ioe) {
            $msg = "An error occurred while reading from pattern file: " . $patternfile->__toString();
            if ($patternReader) {
                $patternReader->close();
            }
            throw new BuildException($msg, $ioe);
        }

        $this->createExcludesFile()->setName($file);
    }

    /**
     * Adds the patterns of the other instance to this set.
     *
     * @param $other
     * @param Project $p
     *
     * @throws \Phing\Exception\BuildException
     */
    public function append($other, $p)
    {
        if ($this->isReference()) {
            throw new BuildException("Cannot append to a reference");
        }

        $incl = $other->getIncludePatterns($p);
        if ($incl !== null) {
            foreach ($incl as $incl_name) {
                $o = $this->createInclude();
                $o->setName($incl_name);
            }
        }

        $excl = $other->getExcludePatterns($p);
        if ($excl !== null) {
            foreach ($excl as $excl_name) {
                $o = $this->createExclude();
                $o->setName($excl_name);
            }
        }
    }

    /**
     * Returns the filtered include patterns.
     *
     * @param Project $p
     *
     * @throws \Phing\Exception\BuildException
     *
     * @return array
     */
    public function getIncludePatterns(Project $p)
    {
        if ($this->isReference()) {
            $o = $this->getRef($p);

            return $o->getIncludePatterns($p);
        } else {
            $this->applyCreators();

            return $this->makeArray($this->includeList, $p);
        }
    }

    /**
     * Returns the filtered exclude patterns.
     *
     * @param Project $p
     *
     * @throws \Phing\Exception\BuildException
     *
     * @return array
     */
    public function getExcludePatterns(Project $p)
    {
        if ($this->isReference()) {
            $o = $this->getRef($p);

            return $o->getExcludePatterns($p);
        } else {
            $this->applyCreators();

            return $this->makeArray($this->excludeList, $p);
        }
    }

    /**
     * helper for FileSet.
     *
     * @return bool
     */
    public function hasPatterns()
    {
        $this->applyCreators();
        return count($this->includeList) > 0 || count($this->excludeList) > 0;
    }

    /**
     * Performs the check for circular references and returns the
     * referenced PatternSet.
     *
     * @param Project $p
     *
     * @throws \Phing\Exception\BuildException
     *
     * @return Reference
     */
    public function getRef(Project $p)
    {
        if (!$this->checked) {
            $stk = [];
            array_push($stk, $this);
            $this->dieOnCircularReference($stk, $p);
        }
        $o = $this->ref->getReferencedObject($p);
        if (!($o instanceof PatternSet)) {
            $msg = $this->ref->getRefId() . " doesn't denote a patternset";
            throw new BuildException($msg);
        } else {
            return $o;
        }
    }

    /**
     * Convert a array of PatternSetNameEntry elements into an array of Strings.
     *
     * @param array $list
     * @param Project $p
     *
     * @return array
     */
    private function makeArray(&$list, Project $p)
    {
        if (count($list) === 0) {
            return null;
        }

        $tmpNames = [];
        foreach ($list as $ne) {
            $pattern = (string)$ne->evalName($p);
            if ($pattern !== null && strlen($pattern) > 0) {
                array_push($tmpNames, $pattern);
            }
        }

        return $tmpNames;
    }

    private function applyCreators()
    {
        /* We could use DataType::parsingComplete() for this, however I'm not
         * sure whether there are any clients directly using PatternSet (withour
         * going through a SAX parser) and so they might not make this call.
         */
        while ($c = array_shift($this->creators)) {
            $c->apply();
        }
    }

    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString()
    {
        $this->applyCreators();

        $includes = implode(", ", $this->includeList);
        $excludes = implode(", ", $this->excludeList);

        return "patternSet{
                includes: { $includes }
                excludes: { $excludes }
         }";
    }
}
