# Path joiner and normalizer #

*PathJoin* is PHP library for normalizing and joining file system paths. The
purpose of this library is to make easier to work with file system paths
irregardless of the platform and the system directory separator.

The purpose of file path normalization is to provide a single consistent file
path representation. In other words, the normalization in this library will
resolve `.` and `..` directory references and also condense multiple directory
separators into one. This makes it much easier to avoid common problems when
comparing paths against each other.

While PHP provides a built in function `realpath()`, it is not usable in every
case since it works by using the file system. This library simply combines and
normalizes the paths using string handling. There is no requirement for the
files or directories to be readable or even exist.

The API documentation, which can be generated using Apigen, can be read online
at: http://kit.riimu.net/api/pathjoin/

[![Build Status](https://img.shields.io/travis/Riimu/Kit-PathJoin.svg?style=flat)](https://travis-ci.org/Riimu/Kit-PathJoin)
[![Coverage Status](https://img.shields.io/coveralls/Riimu/Kit-PathJoin.svg?style=flat)](https://coveralls.io/r/Riimu/Kit-PathJoin?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/Riimu/Kit-PathJoin.svg?style=flat)](https://scrutinizer-ci.com/g/Riimu/Kit-PathJoin/?branch=master)

## Requirements ##

In order to use this library, the following requirements must be met:

  * PHP version 5.4

## Installation ##

This library can be installed via [Composer](http://getcomposer.org/). To do
this, download the `composer.phar` and require this library as a dependency. For
example:

```
$ php -r "readfile('https://getcomposer.org/installer');" | php
$ php composer.phar require riimu/kit-pathjoin:1.*
```

Alternatively, you can add the dependency to your `composer.json` and run
`composer install`. For example:

```json
{
    "require": {
        "riimu/kit-pathjoin": "1.*"
    }
}
```

Any library that has been installed via Composer can be loaded by including the
`vendor/autoload.php` file that was generated by Composer.

It is also possible to install this library manually. To do this, download the
[latest release](https://github.com/Riimu/Kit-PathJoin/releases/latest) and
extract the `src` folder to your project folder. To load the library, include
the provided `src/autoload.php` file.

## Usage ##

This library provides two convenient methods, `Path::normalize()` and
`Path::join()`. Both of these methods work in a very similar fashion. The main
difference is that while the `join()` method can accept multiple paths to join,
the `normalize()` will only accept a single path. Both of the methods will
return a normalized path as the result.

The following example will contain numerous different use cases of the library:

```php
<?php

require 'vendor/autoload.php';
use Riimu\Kit\PathJoin\Path;

// Both of the following will output 'foo/bar' on Unix and 'foo\bar' on Windows
echo Path::normalize('foo/bar') . PHP_EOL;
echo Path::join('foo', 'bar') . PHP_EOL;

// The join method accepts multiple arguments or a single array
echo Path::join('foo', 'bar', 'baz') . PHP_EOL;   // outputs 'foo/bar/baz'
echo Path::join(['foo', 'bar', 'baz']) . PHP_EOL; // outputs 'foo/bar/baz'

// The '.' and '..' directory references will be resolved in the paths
echo Path::normalize('foo/./bar/../baz') . PHP_EOL;     // outputs 'foo/baz'
echo Path::join(['foo/./', 'bar', '../baz']) . PHP_EOL; // outputs 'foo/baz'

// Only the first path can denote an absolute path in the join method
echo Path::join('/foo', '/bar/baz') . PHP_EOL;     // outputs '/foo/bar/baz'
echo Path::join('foo', '/bar') . PHP_EOL;          // outputs 'foo/bar'
echo Path::join('foo', '../bar', 'baz') . PHP_EOL; // outputs 'bar/baz'
echo Path::join('', '/bar', 'baz') . PHP_EOL;      // outputs 'bar/baz'

// Relative paths can start with a '..', but absolute paths cannot
echo Path::join('/foo', '../../bar', 'baz') . PHP_EOL; // outputs '/bar/baz'
echo Path::join('foo', '../../bar', 'baz') . PHP_EOL;  // outputs '../bar/baz'

// Empty paths will result in a '.'
echo Path::normalize('foo/..') . PHP_EOL;
echo Path::join('foo', 'bar', '../..') . PHP_EOL;
```

The `Path::normalize()` also accepts a second parameter `$prependDrive` that
takes a boolean value and defaults to true. On Windows platforms, the drive
letter is important part of the absolute path. Thus, when the parameter is set
to true, the method will prepend the drive letter of the current working
directory to absolute paths if the absolute path does not provide one itself.

The following example is true for Windows systems, if the working directory is
located on the C: drive:

```php
<?php

require 'vendor/autoload.php';
use Riimu\Kit\PathJoin\Path;

echo Path::normalize('/foo/bar') . PHP_EOL;        // outputs 'C:\foo\Bar'
echo Path::normalize('D:/foo/bar') . PHP_EOL;      // outputs 'D:\foo\Bar'
echo Path::normalize('/foo/bar', false) . PHP_EOL; // outputs '\foo\Bar'
```

## Credits ##

This library is copyright 2014 - 2015 to Riikka Kalliomäki.

See LICENSE for license and copying information.
