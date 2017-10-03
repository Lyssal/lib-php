<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Exception;

use RuntimeException as PhpRuntimeException;

/**
 * A run exception.time
 */
class RuntimeException extends PhpRuntimeException
{
    /**
     * {@inheritDoc}
     *
     * @param string $message Message
     */
    public function __construct($message)
    {
        parent::__construct('Runtime exception : '.$message);
    }
}
