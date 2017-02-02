<?php
namespace Phing\Tests\Type\Selector;

use Phing\Type\Selector\SelectorUtils;
use PHPUnit_Framework_TestCase;

/**
 * Class SelectorUtilsTest
 *
 * Test cases for SelectorUtils
 */
class SelectorUtilsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SelectorUtils
     */
    private $selectorUtils;

    protected function setUp()
    {
        $this->selectorUtils = SelectorUtils::getInstance();
    }

    /**
     * Inspired by @link https://www.phing.info/trac/ticket/796
     */
    public function testDoNotIncludeSelfWhenMatchingSubdirectoriesAndFiles()
    {
        $result = $this->selectorUtils->matchPath("**/*", "");
        $this->assertFalse($result);
    }

    public function testUseDotToIncludeSelf()
    {
        // See http://www.phing.info/trac/ticket/724
        $result = $this->selectorUtils->matchPath(".", "");
        $this->assertTrue($result);
    }

    /**
     * Inspired by @link https://www.phing.info/trac/ticket/1264
     */
    public function testDoNotIncludePrefix()
    {
        $this->assertFalse($this->selectorUtils->matchPath("**/example.php", "vendor/phplot/phplot/contrib/color_range.example.php"));
    }

    /**
     * Inspired by @link https://github.com/phingofficial/phing/issues/593
     */
    public function testIncludePathsInBase()
    {
        $this->assertTrue($this->selectorUtils->matchPath("**/domain.ext/**", "domain.ext/foo"));
    }
}
