<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Encoding;

/**
 * Functionalities for the UTF-8 encoding.
 */
class Utf8
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
     * Encode the value.
     */
    public function encode()
    {
        $this->value = $this->encodeValue($this->value);
    }

    /**
     * Encode a value.
     *
     * @param mixed $value The value being encoding
     * @return string The encoded value
     */
    protected function encodeValue($value)
    {
        if (is_string($value)) {
            return utf8_encode($value);
        }

        if (is_array($value)) {
            foreach ($value as $key => $aValue) {
                $value[$key] = $this->encodeValue($aValue);
            }
        }

        return $value;
    }
}
