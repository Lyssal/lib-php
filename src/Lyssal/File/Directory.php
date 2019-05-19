<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\File;

use Lyssal\Exception\IoException;

/**
 * Class to read directories.
 */
class Directory
{
    /**
     * The directory path.
     *
     * @var string
     */
    protected $path;


    /**
     * Constructor.
     *
     * @param string $path The directory path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }


    /**
     * Get the directory path.
     *
     * @return string The path
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Determine if the directory exists.
     *
     * @return bool If it exists
     */
    public function exists(): bool
    {
        return is_dir($this->path);
    }

    /**
     * Create the directory.
     *
     * @throws \Lyssal\Exception\IoException If the directory already exists
     *
     * @return \Lyssal\File\Directory Self
     */
    public function create(): self
    {
        if ($this->exists()) {
            throw new IoException('The directory "'.$this->path.'" already exists.');
        }

        mkdir($this->path);

        return $this;
    }

    /**
     * Get the file count.
     *
     * @return int The count
     */
    public function getFileCount(): int
    {
        return iterator_count(new \FilesystemIterator($this->path, \FilesystemIterator::SKIP_DOTS));
    }

    /**
     * Get the directory file pathnames.
     *
     * @param bool $recursive If files in subdirectories are searched
     * @return string[] The found pathnames
     */
    public function getFilePathnames(bool $recursive = true): array
    {
        return self::getDirectoryFilePathnames($this->path, $recursive);
    }


    /**
     * Get the directory file pathnames.
     *
     * @param string $path      The directory path
     * @param bool   $recursive If files in subdirectories are searched
     * @return string[] The found pathnames
     */
    protected static function getDirectoryFilePathnames(string $path, bool $recursive = true): array
    {
        $pathnames = [];
        $files = scandir($path);

        foreach ($files as $file) {
            $pathname = realpath($path.DIRECTORY_SEPARATOR.$file);

            if (!is_dir($pathname)) {
                $pathnames[] = $pathname;
            } elseif ($recursive && !in_array($file, ['.', '..'])) {
                $pathnames = array_merge($pathnames, self::getDirectoryFilePathnames($pathname, $recursive));
            }
        }

        return $pathnames;
    }
}
