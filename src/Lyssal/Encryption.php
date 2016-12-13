<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal;

/**
 * Encryption.
 */
abstract class Encryption
{
    /**
     * @var string String to crypt or decrypt
     */
    protected $string;


    /**
     * Constructor.
     *
     * @param string $string String
     */
    public function __construct($string)
    {
        $this->string = $string;
    }


    /**
     * Return the string.
     *
     * @return string String
     */
    public function getString()
    {
        return $this->string;
    }
}
