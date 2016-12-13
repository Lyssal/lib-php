<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal;

/**
 * System treatments.
 */
class System
{
    /**
     * Set memory limit.
     *
     * @param string $memoryLimit Memory limit
     */
    public static function setMemoryLimit($memoryLimit)
    {
        ini_set('memory_limit', $memoryLimit);
    }

    /**
     * Set unlimited time limit?
     */
    public static function setUnlimitedTimeLimit()
    {
        set_time_limit(0);
    }

    /**
     * Redirect the user.
     *
     * @param string $url URL redirection
     */
    public static function redirect($url)
    {
        header('Location: '.$url);
        exit();
    }

    /**
     * Get the file upload max size in bytes.
     *
     * @return int The max size
     */
    public static function getFileUploadMaxSize()
    {
        return min(self::getPhpSizeInBytes(ini_get('post_max_size')), self::getPhpSizeInBytes(ini_get('upload_max_filesize')));
    }

    /**
     * Get a PHP size in bytes.
     *
     * @param string $phpSize PHP size (for example 2G or 10M)
     * @return int PHP size
     */
    public static function getPhpSizeInBytes($phpSize)
    {
        if (is_numeric($phpSize)) {
            return $phpSize;
        }

        $sizeSuffix = strtoupper(substr($phpSize, -1));
        $sizeValue = (int) (substr($phpSize, 0, -1));

        switch (strtoupper($sizeSuffix)) {
            case 'P':
                $sizeValue *= 1024;
            case 'T':
                $sizeValue *= 1024;
            case 'G':
                $sizeValue *= 1024;
            case 'M':
                $sizeValue *= 1024;
            case 'K':
                $sizeValue *= 1024;
                break;
        }

        return $sizeValue;
    }
}
