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
use Lyssal\Web\Url;

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
        $this->setPathname($pathname);
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
    protected function initSplFileInfo()
    {
        $this->splFileInfo = null;

        if (!$this->isUrl() && $this->exists()) {
            $this->splFileInfo = new \SplFileInfo($this->pathname);
        }
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
     * Set the file or URL pathname.
     *
     * @param string $pathname The pathname
     */
    public function setPathname($pathname)
    {
        $this->pathname = $pathname;
        $this->initSplFileInfo();
    }

    /**
     * Get the real file or URL pathname.
     *
     * @return string The real pathname
     */
    public function getRealPathname()
    {
        if ($this->isUrl()) {
            return $this->pathname;
        }

        return (null !== $this->splFileInfo ? $this->splFileInfo->getRealPath() : realpath($this->pathname));
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

        return substr($this->pathname, 0, strrpos($this->pathname, $this->getPathDirectorySeparator()));
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

        return substr($this->pathname, strrpos($this->pathname, $this->getPathDirectorySeparator()) + 1);
    }

    /**
     * Get the path directory separator.
     *
     * @return string The path directory separator
     */
    protected function getPathDirectorySeparator(): string
    {
        return $this->isUrl() ? '/' : DIRECTORY_SEPARATOR;
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
     * Change the file extension.
     *
     * @param string|null $extension The extension
     * @param bool        $replace   If the file has to be replaced if already existing or renamed
     *
     * @return $this Self
     */
    public function setExtension(?string $extension, bool $replace = false): self
    {
        $pathname = $this->getPath().$this->getPathDirectorySeparator().$this->getFilenameWithoutExtension();

        if (null !== $extension) {
            $pathname .= '.'.$extension;
        }

        $this->move($pathname, $replace);

        return $this;
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
     * Return if the file has this extension.
     *
     * @param string $extension The extension
     *
     * @return bool If has extension
     */
    public function extensionIs($extension)
    {
        return strtolower($this->getExtension()) === strtolower($extension);
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
        return (false !== filter_var($this->pathname, FILTER_VALIDATE_URL));
    }

    /**
     * Return the URL if it is.
     *
     * @return \Lyssal\Web\Url|null The URL
     */
    public function getUrl()
    {
        if ($this->isUrl()) {
            return new Url($this->getPathname());
        }

        return null;
    }

    /**
     * Return the FileContent object.
     *
     * @return \Lyssal\File\FileContent The FileContent
     */
    public function getFileContent()
    {
        return new FileContent($this);
    }

    /**
     * Get the file content.
     *
     * @return string|null Content or NULL is unreadable
     */
    public function getContent()
    {
        $fileContent = new FileContent($this);
        return $fileContent->get();
    }

    /**
     * Set content in file.
     *
     * @param string $content New content
     * @throws \Lyssal\Exception\IoException If can not write in file
     */
    public function setContent($content)
    {
        $fileContent = new FileContent($this);
        $fileContent->set($content);
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
     * @param string                                         $destinationDirectory The destination directory
     * @param bool                                           $replace              If the file has to be replaced if already existing or renamed
     * @param array|resource|\Lyssal\File\StreamContext|null $streamContext        A Lyssal\File\StreamContext object or a resource stream context or the stream context's options
     * @return bool If the move has successed
     * @throws \Lyssal\Exception\IoException If destination is empty
     */
    public function moveToDirectory($destinationDirectory, $replace = false, $streamContext = null)
    {
        if (DIRECTORY_SEPARATOR !== substr($destinationDirectory, -1)) {
            $destinationDirectory .= DIRECTORY_SEPARATOR;
        }

        return $this->move($destinationDirectory.$this->getFilename(), $replace, $streamContext);
    }

    /**
     * Move the file.
     *
     * @param string                                         $destination   File destination
     * @param bool                                           $replace       If true, replace the file if it already exists at the destination else the file will be renamed if the destination exists
     * @param array|resource|\Lyssal\File\StreamContext|null $streamContext A Lyssal\File\StreamContext object or a resource stream context or the stream context's options
     * @return bool If the move has successed
     * @throws \Lyssal\Exception\IoException If destination is empty
     */
    public function move($destination, $replace = false, $streamContext = null)
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
            $realStreamContext = StreamContext::getRealStreamContext($streamContext);
            if (null === $realStreamContext) {
                $hasMoved = rename($this->getPathname(), $destination);
            } else {
                $hasMoved = rename($this->getPathname(), $destination, $realStreamContext);
            }
        }

        if ($hasMoved) {
            $this->setPathname($destination);
        }

        return $hasMoved;
    }

    /**
     * Copy the file in an directory.
     *
     * @param string                                         $destinationDirectory The destination directory
     * @param bool                                           $replace              If the file has to be replaced if already existing or renamed
     * @param array|resource|\Lyssal\File\StreamContext|null $streamContext        A Lyssal\File\StreamContext object or a resource stream context or the stream context's options
     * @return \Lyssal\File\File|null Created file or NULL if the copy has failed
     * @throws \Lyssal\Exception\IoException If destination is empty
     */
    public function copyToDirectory($destinationDirectory, $replace = false, $streamContext = null)
    {
        if (DIRECTORY_SEPARATOR !== substr($destinationDirectory, -1)) {
            $destinationDirectory .= DIRECTORY_SEPARATOR;
        }

        return $this->copy($destinationDirectory.$this->getFilename(), $replace, $streamContext);
    }

    /**
     * Copy the file.
     *
     * @param string                                         $destination   File destination
     * @param bool                                           $replace       If true, replace the file if it already exists at the destination else the file will be renamed if the destination exists
     * @param array|resource|\Lyssal\File\StreamContext|null $streamContext A Lyssal\File\StreamContext object or a resource stream context or the stream context's options
     * @return \Lyssal\File\File|null Created file or NULL if the copy has failed
     */
    public function copy($destination, $replace = false, $streamContext = null)
    {
        if (false === $replace) {
            $destination = self::getUnoccupiedPathname($destination, self::$SEPARATOR_DEFAULT);
        }

        $realStreamContext = StreamContext::getRealStreamContext($streamContext);
        if (null === $realStreamContext) {
            $hasCopied = copy($this->getPathname(), $destination);
        } else {
            $hasCopied = copy($this->getPathname(), $destination, $realStreamContext);
        }

        if ($hasCopied) {
            return new static($destination);
        }

        return null;
    }

    /**
     * Delete the file.
     *
     * @param array|resource|\Lyssal\File\StreamContext|null $streamContext A Lyssal\File\StreamContext object or a resource stream context or the stream context's options
     * @return bool If success
     */
    public function delete($streamContext = null)
    {
        if ($this->exists()) {
            $realStreamContext = StreamContext::getRealStreamContext($streamContext);
            if (null === $realStreamContext) {
                return unlink($this->getPathname());
            } else {
                return unlink($this->getPathname(), $realStreamContext);
            }
        }

        return false;
    }

    /**
     * Minify the file.
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
