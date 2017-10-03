<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Text;

/**
 * Class to generate a password.
 */
class Password extends AbstractText
{
    /**
     * Generate a random password.
     *
     * @param int $length The password length
     * @return string The generated random password
     */
    public function generate($length = 10)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%*()_-=+;:,.?';

        return substr(str_shuffle($chars), 0, $length );
    }
}
