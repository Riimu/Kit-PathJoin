<?php

namespace Riimu\Kit\PathJoin;

/**
 * Library for joining file system paths in a normalized manner.
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2014, Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class Path
{
    /**
     * Joins and normalizes file systems paths.
     *
     * The method can take either multiple string arguments or an array of
     * strings. The paths will be joined together using a directory separator
     * and any parent and current directory references will be resolved. The
     * resulting path may, however, begin with parent directory references if
     * it is not an absolute path. Only the first path may denote an absolute
     * path, however, since all following paths are relative to the first path.
     *
     * In order to support multiple different platforms, this method will treat
     * all forward and backslashes as directory separators. The resulting path
     * will only contain system directory separators, however.
     *
     * It is possible to simple provide a single path to this function in order
     * to normalize that path.
     *
     * @param string[]|string $path Paths to join and normalize
     * @return string The joined and normalized path
     * @throws \InvalidArgumentException If the path contains invalid characters
     */
    public static function join($path)
    {
        $paths = self::canonize(is_array($path) ? $path : func_get_args(), $absolute);
        $parts = self::normalize($paths, $absolute);
        return $absolute && count($parts) === 1
            ? reset($parts) . DIRECTORY_SEPARATOR
            : implode(DIRECTORY_SEPARATOR, $parts);
    }

    /**
     * Canonizes the path into separate parts regardless of directory separator.
     * @param string[] $args Array of paths
     * @param boolean $absolute Will be set to true if the path is absolute
     * @return string[] Parts in the paths separated into a single array
     */
    private static function canonize(array $args, & $absolute)
    {
        $args = array_map('trim', $args);
        $paths = explode('/', str_replace('\\', '/', implode('/', $args)));
        $absolute = $args[0] !== '' && ($paths[0] === '' || substr($paths[0], -1) === ':');
        return $paths;
    }

    /**
     * Normalizes that parent directory references and removes redundant ones.
     * @param string[] $paths List of parts in the the path
     * @param boolean $absolute Whether the path is an absolute path or not
     * @return string[] Normalized list of paths
     */
    private static function normalize(array $paths, $absolute)
    {
        $parts = $absolute ? [array_shift($paths)] : [];
        $paths = array_filter($paths, [__CLASS__, 'isValidPath']);

        foreach ($paths as $part) {
            if ($part === '..') {
                self::resolveParent($parts, $absolute);
            } else {
                $parts[] = $part;
            }
        }

        return $parts;
    }

    /**
     * Tells if the part of the path is valid and not empty.
     * @param string $path Part of the path to check for redundancy
     * @return bool True if the path is valid and not empty, false if not
     * @throws \InvalidArgumentException If the path contains invalid characters
     */
    private static function isValidPath($path)
    {
        if (strpos($path, ':') !== false) {
            throw new \InvalidArgumentException('Invalid path character ":"');
        }

        return $path !== '' && $path !== '.';
    }

    /**
     * Resolves the relative parent directory for the path.
     * @param string[] $parts Path parts to modify
     * @param boolean $absolute True if dealing with absolute path, false if not
     */
    private static function resolveParent(& $parts, $absolute)
    {
        if (in_array(end($parts), ['..', false], true)) {
            $parts[] = '..';
        } elseif (count($parts) > 1 || !$absolute) {
            array_pop($parts);
        }
    }
}
