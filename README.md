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
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/Riimu/Kit-PathJoin.svg?style=flat)](https://scrutinizer-ci.com/g/Riimu/Kit-PathJoin/)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/Riimu/Kit-PathJoin.svg?style=flat)](https://scrutinizer-ci.com/g/Riimu/Kit-PathJoin/)

## Requirements ##

In order to use this library, the following requirements must be met:

  * PHP version 5.4

## Installation ##

This library can be installed by using [Composer](http://getcomposer.org/). In
order to do this, you must download the latest Composer version and run the
`require` command to add this library as a dependency to your project. The
easiest way to complete these two tasks is to run the following two commands
in your terminal:

```
php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar require "riimu/kit-pathjoin:1.*"
```

If you already have Composer installed on your system and you know how to use
it, you can also install this library by adding it as a dependency to your
`composer.json` file and running the `composer install` command. Here is an
example of what your `composer.json` file could look like:

```json
{
    "require": {
        "riimu/kit-pathjoin": "1.*"
    }
}
```

After installing this library via Composer, you can load the library by
including the `vendor/autoload.php` file that was generated by Composer during
the installation.

### Manual installation ###

You can also install this library manually without using Composer. In order to
do this, you must download the [latest release](https://github.com/Riimu/Kit-PHPEncoder/releases/latest)
and extract the `src` folder from the archive to your project folder. To load
the library, you can simply include the `src/autoload.php` file that was
provided in the archive.

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
