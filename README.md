# Path joiner and normalizer #

This library provides a simple way to join file system paths together while
completely ignoring the fact that different file systems use different
directory separators. In addition to that, this library also resolves any
parent and current directory references in the path (i.e. '..' and '.'), which
creates cleaner file paths.

This library can also be used to normalize file system paths in addition to
joining them, as passing a single argument will simple return that path in a
normalized form.

API documentation can be generated using Apigen or you can simply read it at:
[http://kit.riimu.net/api/pathjoin/](http://kit.riimu.net/api/pathjoin/)

[![Build Status](https://travis-ci.org/Riimu/Kit-PathJoin.svg)](https://travis-ci.org/Riimu/Kit-PathJoin)
[![Coverage Status](https://img.shields.io/coveralls/Riimu/Kit-PathJoin.svg)](https://coveralls.io/r/Riimu/Kit-PathJoin?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Riimu/Kit-PathJoin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Riimu/Kit-PathJoin/?branch=master)

## Requirements ##

This library requires at least PHP 5.4. If you are using earlier versions,
please be aware that they have reached their end of life.

## Installation ##

This library can be easily installed using [Composer](http://getcomposer.org/)
by including the following dependency in your `composer.json`:

```json
{
    "require": {
        "riimu/kit-pathjoin": "1.*"
    }
}
```

The library will be the installed when you run `composer install`. Easiest way
to load the classes is by including the composer class autoloader via
`require 'vendor/autoload.php';`.

## Usage ##

This library provides exactly one method: `Path::join($path, ...)`. This method
can either take paths as multiple arguments or as a single array. The returned
path uses the system directory separators. For example:

```php
<?php

use Riimu\Kit\PathJoin\Path;
echo Path::join('foo', 'bar') . PHP_EOL; // Will output 'foo/bar' or 'foo\bar'
echo Path::join('foo', '/bar/baz') . PHP_EOL; // Will output 'foo/bar/baz' or 'foo\bar\baz'

// Passing an array works fine too
echo Path::join(['foo', 'bar']) . PHP_EOL; // Will output 'foo/bar' or 'foo\bar'

// You can mix and match the directory separators, irregardless of the system
echo Path::join(['/foo', '\bar\baz']) . PHP_EOL;  // Will output '/foo/bar/baz' or '\foo\bar\baz'
```

The method will also traverse any parent directory appropriately. Note that
there is a slight difference in how absolute and relative paths are handled:

```php
<?php

use Riimu\Kit\PathJoin\Path;
echo Path::join('foo/bar', '../baz') . PHP_EOL; // Will output 'foo/baz'
echo Path::join('foo/bar', '../../baz') . PHP_EOL; // Will output 'baz'
echo Path::join('foo/bar', '../../../baz') . PHP_EOL; // Will output '../baz'

echo Path::join('/foo/bar', '../baz') . PHP_EOL; // Will output '/foo/baz'
echo Path::join('/foo/bar', '../../baz') . PHP_EOL; // Will output '/baz'
echo Path::join('/foo/bar', '../../../baz') . PHP_EOL; // Will output '/baz'

// Windows drive names are understood as absolute paths:
echo Path::join('C:\foo\bar', '..\..\..\baz') . PHP_EOL; // Will output 'C:\baz'
```

Only the first path in the path list can determine the path as an absolute path.
All following paths are relative to that path. For example:

```php
<?php

use Riimu\Kit\PathJoin\Path;
echo Path::join('', '/foo') . PHP_EOL; // Will output 'foo'
echo Path::join('/', '/foo') . PHP_EOL; // Will output '/foo'
```

Current directory paths and empty paths are simply discarded. You can also call
the method with just a single argument to normalize that path, like so:

```php
<?php

use Riimu\Kit\PathJoin\Path;
echo Path::join('/foo/.//bar/../baz/') . PHP_EOL; // Will output '/foo/baz'
```

It may be useful to note that the path returned by the method never has a
directory separator in the end.

## Credits ##

This library is copyright 2014 to Riikka Kalliomäki
