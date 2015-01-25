<?php

require '../src/autoload.php';

use Riimu\Kit\PathJoin\Path;
echo Path::join('foo', 'bar') . PHP_EOL; // Will output 'foo/bar' or 'foo\bar'
echo Path::join('foo', '/bar/baz') . PHP_EOL; // Will output 'foo/bar/baz' or 'foo\bar\baz'

// Passing an array works fine too
echo Path::join(['foo', 'bar']) . PHP_EOL; // Will output 'foo/bar' or 'foo\bar'

// You can mix and match the directory separators, irregardless of the system
echo Path::join(['/foo', '\bar\baz']) . PHP_EOL;  // Will output '/foo/bar/baz' or '\foo\bar\baz'

echo Path::join('foo/bar', '../baz') . PHP_EOL; // Will output 'foo/baz'
echo Path::join('foo/bar', '../../baz') . PHP_EOL; // Will output 'baz'
echo Path::join('foo/bar', '../../../baz') . PHP_EOL; // Will output '../baz'

echo Path::join('/foo/bar', '../baz') . PHP_EOL; // Will output '/foo/baz'
echo Path::join('/foo/bar', '../../baz') . PHP_EOL; // Will output '/baz'
echo Path::join('/foo/bar', '../../../baz') . PHP_EOL; // Will output '/baz'

// Windows drive names are understood as absolute paths:
echo Path::join('C:\foo\bar', '..\..\..\baz') . PHP_EOL; // Will output 'C:\baz'

echo Path::join('', '/foo') . PHP_EOL; // Will output 'foo'
echo Path::join('/', '/foo') . PHP_EOL; // Will output '/foo'

echo Path::join('/foo/.//bar/../baz/') . PHP_EOL; // Will output '/foo/baz'
