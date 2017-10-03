<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Exception;

use Exception;

/**
 * A general exception.
 */
class LyssalException extends Exception
{
    /**
     * {@inheritDoc}
     *
     * @param string $message The error message
     * @param int    $code    The error code
     */
    public function __construct($message, $code = 500)
    {
        parent::__construct($message, $code);
    }
}
