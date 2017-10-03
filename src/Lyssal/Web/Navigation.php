<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Web;

/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
class Navigation
{
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
     * Get the current scheme.
     *
     * @return string The scheme
     */
    public static function getScheme()
    {
        $scheme = 'http';
        if (isset($_SERVER['HTTPS']) && 'off' !== $_SERVER['HTTPS']) {
            $scheme = 'https';
        }

        return $scheme;
    }

    /**
     * Get the current host.
     *
     * @return string The host
     */
    public static function getHost()
    {
        return $_SERVER['SERVER_NAME'];
    }

    /**
     * Get the base URL.
     *
     * @return string The base URL
     */
    public static function getBaseUrl()
    {
        return self::getScheme().'://'.self::getHost().'/';
    }

    /**
     * Get the current URL.
     *
     * @return string The current URL
     */
    public static function getCurrentUrl()
    {
        return substr(self::getBaseUrl(), 1).$_SERVER['REQUEST_URI'];
    }
}
