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
namespace Phing\Test\Io\FileParser;

use Phing\Io\FileParser\FileParserFactory;
use Phing\Io\FileParser\IniFileParser;
use Phing\Io\FileParser\YamlFileParser;
use PHPUnit_Framework_TestCase;

/**
 * Unit test for FileParserFactory
 *
 * @author Mike Lohmann <mike.lohmann@deck36.de>
 * @package phing.system.io
 */
class FileParserFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var FileParserFactory
     */
    private $objectToTest;

    /**
     * @var string
     */
    private $iniFileStub;

    /**
     * @{inheritDoc}
     */
    public function setUp()
    {
        $this->objectToTest = new FileParserFactory();
    }

    /**
     * @{inheritDoc}
     */
    public function tearDown()
    {
        $this->objectToTest = null;
    }

    /**
     * @covers       \Phing\Io\FileParser\FileParserFactory::createParser
     * @dataProvider parserTypeProvider
     */
    public function testCreateParser($parserName, $expectedType)
    {
        $this->assertInstanceOf($expectedType, $this->objectToTest->createParser($parserName));
    }

    /**
     * @return array
     */
    public function parserTypeProvider()
    {
        return [
            ['properties', IniFileParser::class],
            ['ini', IniFileParser::class],
            ['foo', IniFileParser::class],
            ['yml', YamlFileParser::class],
            ['yaml', YamlFileParser::class],
        ];
    }
}
