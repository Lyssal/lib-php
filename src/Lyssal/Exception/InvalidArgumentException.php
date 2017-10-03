<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Exception;

use InvalidArgumentException as PhpInvalidArgumentException;

/**
 * An invalid argument exception.
 */
class InvalidArgumentException extends PhpInvalidArgumentException
{
    /**
     * {@inheritDoc}
     *
     * @param string $message Message
     */
    public function __construct($message)
    {
        parent::__construct('Invalid argument : '.$message);
    }
}
