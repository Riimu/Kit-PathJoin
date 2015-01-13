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
     * and any backtracking, i.e. '..', will be resolved. The method assumes
     * that both forward and backward slash are directory separators to provide
     * best possible cross platform compatibility. Only the first path can
     * indicate an absolute path. All following paths are always relative to
     * the first path.
     *
     * A single string can be passed to this method in order to normalize any
     * '..' in the path and to assure platform appropriate directory separators.
     * Any '.' directory or an empty directory name, i.e. '', will be discarded.
     *
     * @param string[]|string $path
     * @return string The joined and normalized path
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
     * Normalizes that parent directory references and removes unnecessary ones.
     * @param string[] $paths List of parts in the the path
     * @param boolean $absolute Whether the path is an absolute path or not
     * @return string[] Normalized list of paths
     * @throws \InvalidArgumentException If the path contains invalid colons
     */
    private static function normalize(array $paths, $absolute)
    {
        $parts = $absolute ? [array_shift($paths)] : [];
        $paths = array_filter($paths, [__CLASS__, 'emptyPath']);

        foreach ($paths as $part) {
            if ($part === '..') {
                self::ascend($part, $parts, $absolute);
            } else {
                $parts[] = $part;
            }
        }

        return $parts;
    }

    private static function emptyPath($path)
    {
        if (strpos($path, ':') !== false) {
            throw new \InvalidArgumentException('Invalid path character ":"');
        }

        return $path !== '' && $path !== '.';
    }

    private static function ascend($part, & $parts, $absolute)
    {
        if (in_array(end($parts), ['..', false], true)) {
            $parts[] = '..';
        } elseif (count($parts) > 1 || !$absolute) {
            array_pop($parts);
        }
    }
}
