<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Exception;

/**
 * Input / Output exception.
 */
class IoException extends LyssalException
{
    /**
     * {@inheritDoc}
     *
     * @param string $message Message
     */
    public function __construct($message)
    {
        parent::__construct('Input/output error : '.$message);
    }
}
