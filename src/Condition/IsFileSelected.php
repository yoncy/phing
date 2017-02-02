<?php
/**
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
use Phing\Io\File;
use Phing\Type\Selector\AbstractSelectorContainer;
use Phing\Type\Selector\FileSelectorInterface;

/**
 * This is a condition that checks to see if a file passes an embedded selector.
 */
class IsFileSelected extends AbstractSelectorContainer implements ConditionInterface
{
    /** @var File $file */
    private $file;
    private $baseDir;

    /**
     * The file to check.
     * @param File $file the file to check if if passes the embedded selector.
     */
    public function setFile(File $file)
    {
        $this->file = $file;
    }

    /**
     * The base directory to use.
     * @param File $baseDir the base directory to use, if null use the project's
     *                basedir.
     */
    public function setBaseDir(File $baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * validate the parameters.
     */
    public function validate()
    {
        if ($this->selectorCount() != 1) {
            throw new BuildException("Only one selector allowed");
        }
        parent::validate();
    }

    /**
     * Evaluate the selector with the file.
     * @return true if the file is selected by the embedded selector.
     */
    public function evaluate()
    {
        if ($this->file === null) {
            throw new BuildException('file attribute not set');
        }
        $this->validate();
        $myBaseDir = $this->baseDir;
        if ($myBaseDir === null) {
            $myBaseDir = $this->getProject()->getBaseDir();
        }

        /** @var FileSelectorInterface $f */
        $file = $this->getSelectors($this->getProject());
        $f = $file[0];
        return $f->isSelected($myBaseDir, $this->file->getName(), $this->file);
    }

    /**
     * Method that each selector will implement to create their
     * selection behaviour. If there is a problem with the setup
     * of a selector, it can throw a BuildException to indicate
     * the problem.
     *
     * @param File $basedir A File object for the base directory
     * @param string $filename The name of the file to check
     * @param File $file A File object for this filename
     * @return bool whether the file should be selected or not
     * @throws BuildException if the selector was not configured correctly
     */
    public function isSelected(File $basedir, $filename, File $file)
    {
        return $this->getSelectors($this->getProject())[0]->isSelected($basedir, $filename, $file);
    }
}
