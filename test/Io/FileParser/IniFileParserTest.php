<?php
namespace Phing\Test\Io\FileParser;

use Phing\Io\File;
use Phing\Io\FileParser\IniFileParser;
use Phing\Util\Properties\PropertySetInterface;
use Phing\Util\Properties\PropertySetImpl;

class IniFileParserTest extends \PHPUnit_Framework_TestCase {

    /** @var PropertySetInterface */
    protected $props;
    
    /** @var IniFileParser */
    protected $parser;

    protected function setUp()
    {
        $this->props = new PropertySetImpl();
        $this->parser = new IniFileParser();
    }
    
    public function testReadingFileStripsComments()
    {
        $file = new File(PHING_TEST_BASE . "/etc/system/util/test.properties");
        $this->parser->parseFile($file, $this->props);

        $this->assertEquals('Testline1', $this->props['testline1']); // http://www.phing.info/trac/ticket/585
        $this->assertEquals('Testline2', $this->props['testline2']); // http://www.phing.info/trac/ticket/585
        $this->assertEquals('ThisIs#NotAComment', $this->props['testline3']);
        $this->assertEquals('ThisIs;NotAComment', $this->props['testline4']);
        $this->assertEquals('This is a multiline value.', $this->props['multiline']);

        $this->assertEquals(5, count(iterator_to_array($this->props->getIterator())));
    } 

    public function testReadingArrayProperties()
    {
        $file = new File(PHING_TEST_BASE . "/etc/system/util/array.properties");
        $this->parser->parseFile($file, $this->props);

        $this->assertEquals(array('first', 'second', 'test' => 'third'), $this->props['array']);
        $this->assertEquals(array('one' => 'uno', 'two' => 'dos'), $this->props['keyed']);
    }

    public function testDoesNotAttemptPropertyExpansion()
    {
        $file = new File(PHING_TEST_BASE . "/etc/system/util/expansion.properties");
        $this->parser->parseFile($file, $this->props);

        $this->assertEquals('${a}bar', $this->props['b']);
    }

    public function testReadingGlobalSection()
    {
        $file = new File(PHING_TEST_BASE . "/etc/system/util/sections.properties");
        $this->parser->parseFile($file, $this->props);

        $this->assertEquals('global', $this->props['global']);
        $this->assertEquals('global', $this->props['section']);
        $this->assertFalse(isset($this->props['inherited']));
    }

    public function testReadingSimpleSection()
    {
        $file = new File(PHING_TEST_BASE . "/etc/system/util/sections.properties");
        $this->parser->parseFile($file, $this->props, 'top');

        $this->assertEquals('global', $this->props['global']);
        $this->assertEquals('top', $this->props['section']);
        $this->assertEquals('from-top', $this->props['inherited']);
    }

    public function testReadingInheritedSection()
    {
        $file = new File(PHING_TEST_BASE . "/etc/system/util/sections.properties");
        $this->parser->parseFile($file, $this->props, 'inherited');

        $this->assertEquals('global', $this->props['global']);
        $this->assertEquals('inherited', $this->props['section']);
        $this->assertEquals('from-top', $this->props['inherited']);
    }

    public function testReadingBooleans()
    {
        $file = new File(PHING_TEST_BASE . "/etc/system/util/booleans.properties");
        $this->parser->parseFile($file, $this->props);

        $this->assertTrue($this->props['true']);
        $this->assertFalse($this->props['false']);
    }

    /**
     * @expectedException \Phing\Io\IOException
     */
    public function testLoadNonexistentFileThrowsException()
    {
        $file = new File(PHING_TEST_BASE . "/etc/system/util/nonexistent.properties");
        $this->parser->parseFile($file, $this->props);
    }

    /**
     * @dataProvider provideIniFiles
     * @covers IniFileParser::parseFile
     * @covers IniFileParser::inVal
     */
    public function testParseFile($data, $expected)
    {
        $root = \org\bovigo\vfs\vfsStream::setup();
        $file = $root->url() . '/test';
        file_put_contents($file, $data);

        $phingFile = new File($file);
        $this->parser->parseFile($phingFile, $this->props);
        
        foreach ($expected as $key => $value) {
            $this->assertSame($value, $this->props[$key]);
        }
    }

    /**
     * @return array
     */
    public function provideIniFiles()
    {
        return [
            [
                'data'     => "property = test\nproperty2 = test2\nproperty3 = test3\n",
                'expected' => [
                    'property'  => 'test',
                    'property2' => 'test2',
                    'property3' => 'test3',
                ],
            ],
            [
                'data'     => "property = test\r\nproperty2 = test2\r\nproperty3 = test3\r\n",
                'expected' => [
                    'property'  => 'test',
                    'property2' => 'test2',
                    'property3' => 'test3',
                ],
            ],
            [
                'data'     => "property = test,\\\ntest2,\\\ntest3\n",
                'expected' => [
                    'property' => 'test,test2,test3',
                ],
            ],
            [
                'data'     => "property = test,\\\r\ntest2,\\\r\ntest3\r\n",
                'expected' => [
                    'property' => 'test,test2,test3',
                ],
            ],
            [
                'data'     => "# property = test",
                'expected' => [],
            ],
            [
                'data'     => "   # property = test",
                'expected' => [],
            ],
            [
                'data'     => "; property = test",
                'expected' => [],
            ],
            [
                'data'     => "property=test",
                'expected' => [
                    'property' => 'test',
                ],
            ],
            [
                'data'     => "property = true",
                'expected' => [
                    'property' => true,
                ],
            ],
            [
                'data'     => "property = false",
                'expected' => [
                    'property' => false,
                ],
            ],
        ];
    }
} 
