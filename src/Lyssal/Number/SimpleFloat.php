<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Number;

/**
 * Class to manipulate a float.
 */
class SimpleFloat
{
    /**
     * @var float The float
     */
    protected $float;


    /**
     * Constructor.
     *
     * @param float $float The float to manage
     */
    public function __construct($float)
    {
        $this->float = $float;
    }

    /**
     * Return the manipulated float.
     *
     * @return float The float
     */
    public function getFloat()
    {
        return $this->float;
    }

    /**
     * Set the float.
     *
     * @param float $float Tthe new float
     */
    public function setFloat($float)
    {
        $this->float = $float;
    }


    /**
     * Delete accents.
     *
     * @return \Lyssal\Text\SimpleString String without accent
     */
    public static function parse($number)
    {
        return (float) str_replace($number, ',', '.');
    }
}
