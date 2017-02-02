<?php

namespace Phing\Filter;

use Exception;
use Phing\Io\AbstractReader;

/**
 * Filter which includes only those lines that contain all the user-specified
 * strings.
 *
 * Example:
 *
 * <pre><linecontains>
 *   <contains value="foo">
 *   <contains value="bar">
 * </linecontains></pre>
 *
 * Or:
 *
 * <pre><filterreader classname="phing.filters.LineContains">
 *    <param type="contains" value="foo"/>
 *    <param type="contains" value="bar"/>
 * </filterreader></pre>
 *
 * This will include only those lines that contain <code>foo</code> and
 * <code>bar</code>.
 *
 * @author    Yannick Lecaillez <yl@seasonfive.com>
 * @author    Hans Lellelid <hans@velum.net>
 * @version   $Id$
 * @see       PhingFilterReader
 * @package   phing.filters
 */
class LineContains extends BaseParamFilterReader implements ChainableReaderInterface
{

    /**
     * The parameter name for the string to match on.
     * @var string
     */
    const CONTAINS_KEY = "contains";

    /**
     * Array of Contains objects.
     * @var array
     */
    private $_contains = [];

    /**
     * Returns all lines in a buffer that contain specified strings.
     * @param null $len
     * @return mixed buffer, -1 on EOF
     */
    public function read($len = null)
    {
        if (!$this->getInitialized()) {
            $this->_initialize();
            $this->setInitialized(true);
        }

        $buffer = $this->in->read($len);

        if ($buffer === -1) {
            return -1;
        }

        $lines = explode("\n", $buffer);
        $matched = [];
        $containsSize = count($this->_contains);

        foreach ($lines as $line) {
            for ($i = 0; $i < $containsSize; $i++) {
                $containsStr = $this->_contains[$i]->getValue();
                if (strstr($line, $containsStr) === false) {
                    $line = null;
                    break;
                }
            }
            if ($line !== null) {
                $matched[] = $line;
            }
        }
        $filtered_buffer = implode("\n", $matched);

        return $filtered_buffer;
    }

    /**
     * Adds a <code>contains</code> nested element.
     *
     * @return Contains The <code>contains</code> element added.
     *                  Must not be <code>null</code>.
     */
    public function createContains()
    {
        $num = array_push($this->_contains, new Contains());

        return $this->_contains[$num - 1];
    }

    /**
     * Sets the array of words which must be contained within a line read
     * from the original stream in order for it to match this filter.
     *
     * @param array $contains An array of words which must be contained
     *                        within a line in order for it to match in this filter.
     *                        Must not be <code>null</code>.
     * @throws Exception
     */
    public function setContains($contains)
    {
        // type check, error must never occur, bad code of it does
        if (!is_array($contains)) {
            throw new Exception("Excpected array got something else");
        }

        $this->_contains = $contains;
    }

    /**
     * Returns the vector of words which must be contained within a line read
     * from the original stream in order for it to match this filter.
     *
     * @return array The array of words which must be contained within a line read
     *               from the original stream in order for it to match this filter. The
     *               returned object is "live" - in other words, changes made to the
     *               returned object are mirrored in the filter.
     */
    public function getContains()
    {
        return $this->_contains;
    }

    /**
     * Creates a new LineContains using the passed in
     * Reader for instantiation.
     *
     * @param AbstractReader $reader A Reader object providing the underlying stream.
     *                       Must not be <code>null</code>.
     *
     * @return LineContains A new filter based on this configuration, but filtering
     *                      the specified reader
     */
    public function chain(AbstractReader $reader)
    {
        $newFilter = new LineContains($reader);
        $newFilter->setContains($this->getContains());
        $newFilter->setInitialized(true);
        $newFilter->setProject($this->getProject());

        return $newFilter;
    }

    /**
     * Parses the parameters to add user-defined contains strings.
     */
    private function _initialize()
    {
        $params = $this->getParameters();
        if ($params !== null) {
            foreach ($params as $param) {
                if (LineContains::CONTAINS_KEY == $param->getType()) {
                    $cont = new Contains();
                    $cont->setValue($param->getValue());
                    array_push($this->_contains, $cont);
                    break; // because we only support a single contains
                }
            }
        }
    }
}
