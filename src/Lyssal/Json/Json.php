<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Json;

use Lyssal\Encoding\Utf8;
use Lyssal\Exception\RuntimeException;

/**
 * Some functionalities with Json.
 */
class Json
{
    /**
     * @var mixed Value
     */
    protected $value;


    /**
     * Constructor.
     *
     * @param mixed $value The value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }


    /**
     * Return the manipulated value.
     *
     * @return string The value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value.
     *
     * @param string $value The new value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }


    /**
     * Encode the value in JSON.
     *
     * @see json_encode() for parameters
     *
     * @param int   $options The bitmask
     * @param int   $depth   The maximum depth
     *
     * @throws \Lyssal\Exception\RuntimeException If the encoding has failed
     */
    public function encode($options = 0, $depth = 512)
    {
        $json = json_encode($this->value, $options, $depth);

        if (false !== $json) {
            $this->value = $json;
        } else {
            switch (json_last_error()) {
                case JSON_ERROR_UTF8:
                    $utf8Value = new Utf8($this->value);
                    $utf8Value->encode();
                    $this->value = $utf8Value->getValue();
                    $this->encode($options, $depth);
                    break;
                default:
                    throw new RuntimeException('An error occurred while the JSON encoding (code error '.json_last_error().').');
            }
        }
    }
}
