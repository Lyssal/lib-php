<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\File;

use finfo;
use Lyssal\Exception\IoException;
use Lyssal\Text\SimpleString;
use Lyssal\Text\Slug;

/**
 * Class to manipulate files.
 */
class File
{
    /**
     * @var string Séparateur de slug par défaut
     */
    protected static $SEPARATOR_DEFAULT = '-';


    /**
     * @var string File pathname
     */
    protected $pathname;

    /**
     * @var \SplFileInfo|null SplFileInfo
     */
    protected $splFileInfo;


    /**
     * Constructor.
     *
     * @param string $pathname Pathname
     */
    public function __construct($pathname)
    {
        $this->pathname = $pathname;

        if (file_exists($pathname)) {
            $this->initSplFileInfo($pathname);
        }
    }


    /**
     * Set the default separator.
     *
     * @param string $separator Separator
     */
    public static function setDefaultSeparator($separator)
    {
        self::$SEPARATOR_DEFAULT = $separator;
    }

    /**
     * Init SplFileInfo.
     *
     * @param string $pathname Pathname
     */
    protected function initSplFileInfo($pathname)
    {
        $this->splFileInfo = new \SplFileInfo($pathname);
    }


    /**
     * Get the file or URL pathname.
     *
     * @return string Pathname
     */
    public function getPathname()
    {
        return (null !== $this->splFileInfo ? $this->splFileInfo->getRealPath() : $this->pathname);
    }

    /**
     * Get the file or URL path.
     *
     * @return string Path
     */
    public function getPath()
    {
        if (null !== $this->splFileInfo) {
            return $this->splFileInfo->getPath();
        }

        return (
            $this->isUrl()
                ? substr($this->pathname, 0, strrpos($this->pathname, '/'))
                : substr($this->pathname, 0, strrpos($this->pathname, DIRECTORY_SEPARATOR))
        );
    }

    /**
     * Get the file or URL name.
     *
     * @return string Filename
     */
    public function getFilename()
    {
        if (null !== $this->splFileInfo) {
            return $this->splFileInfo->getFilename();
        }

        return (
            $this->isUrl()
                ? substr($this->pathname, strrpos($this->pathname, '/') + 1)
                : substr($this->pathname, strrpos($this->pathname, DIRECTORY_SEPARATOR) + 1)
        );
    }

    /**
     * Return if file has an extension.
     *
     * @return bool If has an extension
     */
    public function hasExtension()
    {
        return (null !== $this->getExtension());
    }

    /**
     * Get the file / URL extension.
     *
     * @return string Extension
     */
    public function getExtension()
    {
        $extension = (
            null !== $this->splFileInfo
                ? $this->splFileInfo->getExtension()
                : substr($this->pathname, strrpos($this->pathname, '.') + 1)
        );

        return ('' !== $extension ? $extension : null);
    }

    /**
     * Get filename without its extension.
     *
     * @return string Filename withour extension
     */
    public function getFilenameWithoutExtension()
    {
        if (null !== $this->getExtension()) {
            return substr($this->getFilename(), 0, - strlen($this->getExtension()) - 1);
        }

        return $this->getFilename();
    }


    /**
     * Return if file exists.
     *
     * @return bool If exists
     */
    public function exists()
    {
        if (!$this->isUrl()) {
            return file_exists($this->getPathname());
        }

        $url = new Url($this->getPathname());
        return $url->exists();
    }

    /**
     * Get the file size (in bytes).
     *
     * @return integer|null Size
     */
    public function getSize()
    {
        if ($this->exists()) {
            return filesize($this->getPathname());
        }
        if (null !== $this->splFileInfo) {
            return $this->splFileInfo->getSize();
        }

        return null;
    }

    /**
     * Get the mime type of the file.
     *
     * @return string The mime type
     */
    public function getMimeType()
    {
        $fileinfo = new finfo();
        return $fileinfo->file($this->getPathname(), FILEINFO_MIME_TYPE);
    }

    /**
     * Return if file is an URL.
     *
     * @return boolean If URL
     */
    public function isUrl()
    {
        return (false !== filter_var($this->pathname, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED));
    }

    /**
     * Get the file content.
     *
     * @return string|null Content or NULL is unreadable
     */
    public function getContent()
    {
        $content = file_get_contents($this->splFileInfo->getRealPath());

        if (false === $content) {
            $content = null;
        }

        return $content;
    }

    /**
     * Set content in file.
     *
     * @param string $content New content
     * @throws \Lyssal\Exception\IoException If can not write in file
     */
    public function setContent($content)
    {
        $contentSize = file_put_contents($this->getPathname(), $content);

        if (false === $contentSize) {
            throw new IoException('Not authorized to write in "'.$this->getFilename().'".');
        } else {
            clearstatcache(true, $this->getPathname());
        }
    }

    /**
     * Try to get the file encoding.
     *
     * @return string|null Encoding
     */
    public function getEncoding()
    {
        $encoding = mb_detect_encoding(file_get_contents($this->splFileInfo->getRealPath(), null, null, 1), mb_list_encodings());

        return (false !== $encoding ? $encoding : null);
    }


    /**
     * Move the file to an directory.
     *
     * @param string $destinationDirectory The destination directory
     * @param bool   $replace              If the file has to be replaced if already existing or renamed
     * @return bool If the move has successed
     * @throws \Lyssal\Exception\IoException If destination is empty
     */
    public function moveToDirectory($destinationDirectory, $replace = false)
    {
        if (DIRECTORY_SEPARATOR !== substr($destinationDirectory, -1)) {
            $destinationDirectory .= DIRECTORY_SEPARATOR;
        }

        return $this->move($destinationDirectory.$this->getFilename(), $replace);
    }

    /**
     * Move the file.
     *
     * @param string $destination File destination
     * @param bool   $replace     If true, replace the file if it already exists at the destination else the file will be renamed if the destination exists
     * @return bool If the move has successed
     * @throws \Lyssal\Exception\IoException If destination is empty
     */
    public function move($destination, $replace = false)
    {
        if (empty($destination)) {
            throw new IoException('Cannot move file in an empty destination.');
        }

        if (false === $replace) {
            $destination = self::getUnoccupiedPathname($destination, self::$SEPARATOR_DEFAULT);
        }

        if (is_uploaded_file($this->getPathname())) {
            $hasMoved = move_uploaded_file($this->getPathname(), $destination);
        } else {
            $hasMoved = rename($this->getPathname(), $destination);
        }

        if ($hasMoved) {
            $this->initSplFileInfo($destination);
        }

        return $hasMoved;
    }

    /**
     * Copy the file in an directory.
     *
     * @param string $destinationDirectory The destination directory
     * @param bool   $replace              If the file has to be replaced if already existing or renamed
     * @return \Lyssal\File\File|null Created file or NULL if the copy has failed
     * @throws \Lyssal\Exception\IoException If destination is empty
     */
    public function copyToDirectory($destinationDirectory, $replace = false)
    {
        if (DIRECTORY_SEPARATOR !== substr($destinationDirectory, -1)) {
            $destinationDirectory .= DIRECTORY_SEPARATOR;
        }

        return $this->copy($destinationDirectory.$this->getFilename(), $replace);
    }

    /**
     * Copy the file.
     *
     * @param string $destination File destination
     * @param bool   $replace     If true, replace the file if it already exists at the destination else the file will be renamed if the destination exists
     * @return \Lyssal\File\File|null Created file or NULL if the copy has failed
     */
    public function copy($destination, $replace = false)
    {
        if (false === $replace) {
            $destination = self::getUnoccupiedPathname($destination, self::$SEPARATOR_DEFAULT);
        }

        if (copy($this->getPathname(), $destination)) {
            return new static($destination);
        }

        return null;
    }

    /**
     * Delete the file.
     *
     * @return bool If success
     */
    public function delete()
    {
        if ($this->exists()) {
            return unlink($this->getPathname());
        }

        return false;
    }

    /**
     * Minify the filename.
     *
     * @param string $filename  (optional) The new filename ; by default the filename of the current File ; the extension is optionnal
     * @param string $separator (optional) The separator which replace special caracters
     * @param bool   $lowercase (optional) If the filename will be lowercased (yes by default)
     * @param int    $maxlength (optional) The max length of the filename (with extension)
     * @param bool   $replace   (optional) If true, the destination will be replaced if existing, else (by defaut), the minification will use a unexisting filename
     * @return \Lyssal\File\File This
     * @throws \Lyssal\Exception\IoException If the file cannot be minified
     */
    public function minify($filename = null, $separator = null, $lowercase = true, $maxlength = null, $replace = false)
    {
        if (null === $filename) {
            $filename = $this->getFilename();
        }
        if ('' === $filename) {
            throw new IoException('Cannot minify the file. Please choose an other file name.');
        }

        $separator = (null !== $separator ? $separator : self::$SEPARATOR_DEFAULT);
        $extensionDotPosition = strrpos($filename, '.');
        if (false === $extensionDotPosition) {
            $extensionDotPosition = strlen($filename);
            $extension = $this->getExtension();
        } else {
            $extension = substr($filename, $extensionDotPosition + 1);
        }
        if ($lowercase) {
            $extension = strtolower($extension);
        }
        $hasExtension = (null !== $extension);
        $filenameWithoutExtension = ($hasExtension ? substr($filename, 0, $extensionDotPosition) : $filename);

        $filenameString = new SimpleString($filenameWithoutExtension);
        $filenameString->minify($separator, $lowercase);
        $minifiedFilenameWithoutExtension = $filenameString->getText();

        $minifiedFile = new File(
            $replace
                ? $this->getPath().DIRECTORY_SEPARATOR.$minifiedFilenameWithoutExtension.($hasExtension ? '.'.$extension : '')
                : self::getUnoccupiedPathname($this->getPath().DIRECTORY_SEPARATOR.$minifiedFilenameWithoutExtension.($hasExtension ? '.'.$extension : ''), $separator)
        );
        $minifiedFilenameWithoutExtension = $minifiedFile->getFilenameWithoutExtension();

        if (null !== $maxlength && strlen($minifiedFilenameWithoutExtension) > $maxlength) {
            $maxlengthSubstract =
                ($hasExtension ? strlen($extension) + 1 : 0)
                //+ ($replace ? 0 : strlen($minifiedFile->getPathname()) - strlen($this->getPathname()))
            ;

            $minifiedFilenameWithoutExtension = substr($minifiedFilenameWithoutExtension, 0, $maxlength - $maxlengthSubstract);
            if ($filename === $minifiedFilenameWithoutExtension) {
                $minifiedFilenameWithoutExtension = substr($minifiedFilenameWithoutExtension, 0, -1);
            }

            return $this->minify($minifiedFilenameWithoutExtension.($hasExtension ? '.'.$extension : ''), $separator, $lowercase, $maxlength, $replace);
        }

        $this->move($this->getPath().DIRECTORY_SEPARATOR.$minifiedFilenameWithoutExtension.($hasExtension ? '.'.$extension : ''), $replace);

        return $this;
    }

    /**
     * Normalize the end of lines.
     *
     * @throws \Lyssal\Exception\IoException If the file cannot be read
     */
    public function normalizeEndLines()
    {
        $content = $this->getContent();
        if (null === $content) {
            throw new IoException('The file cannot be read.');
        }
        $content = str_replace(array("\r", "\n"), "\r\n", $content);

        $this->setContent($content);
    }


    /**
    * Get an unoccupied pathname : The same if unoccupied else an other pathname.
    *
    * @param string $pathname  The file pathname
    * @param string $separator (optional) The separator if the original pathname is occupied
    * @return string A unoccupied pathname
    */
    public static function getUnoccupiedPathname($pathname, $separator = null)
    {
        $file = new File($pathname);

        if ($file->exists()) {
            if (null === $separator) {
                $separator = self::$SEPARATOR_DEFAULT;
            }

            $pathnameExtension = $file->getExtension();
            $pathnameWithoutExtension = $file->getPath().DIRECTORY_SEPARATOR.$file->getFilenameWithoutExtension();
            $pathnameSlug = new Slug($pathnameWithoutExtension);
            $pathnameSlug->next($separator);

            return self::getUnoccupiedPathname($pathnameSlug->getText().(null !== $pathnameExtension ? '.'.$pathnameExtension : ''), $separator);
        }

        return $file->getPathname();
    }
}
