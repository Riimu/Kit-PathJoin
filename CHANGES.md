# Changelog #

## v1.2.0 (2017-07-09) ##

  * Minor changes to path joining logic
  * Increased minimum required PHP version to 5.6
  * Updated tests to work with PHPUnit 6
  * Updated travis build
  * Slightly improved the bundled autoloader

## v1.1.2 (2015-08-22) ##

  * Slightly reworked how the paths are built

## v1.1.1 (2015-08-09) ##

  * Maintenance release that simply addresses some coding standards issues

## v1.1.0 (2015-03-25) ##

  * Added `Path::normalize()` method for normalizing a single path.
  * The `Path::join()` method now correctly returns '.' instead of an empty
    path, similar to the `dirname()` function.

## v1.0.1 (2015-01-24) ##

  * Improvements in code quality and documentation
