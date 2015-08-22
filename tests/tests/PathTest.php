<?php

namespace Riimu\Kit\PathJoin;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2014, Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class PathTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyPathArray()
    {
        $this->setExpectedException('InvalidArgumentException');
        Path::join([]);
    }

    public function testArgumentVariations()
    {
        $this->assertPath(['foo', 'bar', 'baz'], Path::join('foo', 'bar', 'baz'));
        $this->assertPath(['foo', 'bar', 'baz'], Path::join(['foo', 'bar', 'baz']));
        $this->assertPath(['foo', 'bar', 'baz'], Path::join('foo', 'bar/baz'));
        $this->assertPath(['foo', 'bar', 'baz'], Path::join(['foo', 'bar/baz']));
    }

    public function testEmptyAbsolutePaths()
    {
        $this->assertPath(['', ''], Path::join('/', ''));
        $this->assertPath(['', ''], Path::join('\\', ''));
        $this->assertPath(['C:', ''], Path::join('C:\\', ''));
        $this->assertPath(['C:', ''], Path::join('C:', ''));
    }

    public function testAbsolutePaths()
    {
        $this->assertPath(['', 'foo', 'bar'], Path::join('/foo', 'bar'));
        $this->assertPath(['C:', 'foo', 'bar'], Path::join('C:/foo', 'bar'));
    }

    public function testRelativePaths()
    {
        $this->assertPath(['.'], Path::join('', '/', '//'));
        $this->assertPath(['foo', 'bar'], Path::join('foo', '/bar'));
    }

    public function testDirectorySeparators()
    {
        $this->assertPath(['', 'foo', 'bar', 'baz'], Path::join('\foo\bar\baz'));
        $this->assertPath(['', 'foo', 'bar', 'baz'], Path::join('/foo/bar/baz'));
    }

    public function testInvalidColon()
    {
        $this->setExpectedException('\InvalidArgumentException');
        Path::join('foo', 'C:\bar');
    }

    public function testSpecialDirectories()
    {
        $this->assertPath(['', 'foo', 'baz'], Path::join('/foo/bar/../baz'));
        $this->assertPath(['', 'foo', 'bar', 'baz'], Path::join('/foo/bar/./baz'));
    }

    public function testRelativeBacktracking()
    {
        $this->assertPath(['.'], Path::join('foo/bar', '..', '/..'));
        $this->assertPath(['..', 'baz'], Path::join('foo/bar', '..', '/../../', 'baz'));
    }

    public function testAbsoluteBacktracking()
    {
        $this->assertPath(['', ''], Path::join('/foo/bar', '..', '/..'));
        $this->assertPath(['', 'baz'], Path::join('/foo/bar', '..', '/../../', 'baz'));
    }

    public function testWindowsAbsolutePaths()
    {
        $this->assertPath(['C:', 'baz'], Path::join('C:/foo/bar', '..', '/../../', 'baz'));
    }

    public function testNormalization()
    {
        $this->assertPath(['foo', 'bar'], Path::normalize('foo/bar'));
    }

    public function testDriveNormalization()
    {
        $this->assertPath([strstr(getcwd(), DIRECTORY_SEPARATOR, true), 'foo', 'bar'], Path::normalize('/foo/bar'));
    }

    public function testEmptyPath()
    {
        $this->assertSame('.', Path::normalize(''));
    }

    public function testZeroAsEmptyPath()
    {
        $this->assertSame('0', Path::normalize('0'));
    }

    private function assertPath(array $expected, $actual)
    {
        $this->assertSame(implode(DIRECTORY_SEPARATOR, $expected), $actual);
    }
}
