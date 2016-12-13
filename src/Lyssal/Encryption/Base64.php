<?php
/**
 * This file is part of the Lyssal PHP library.
 *
 * @copyright Rémi Leclerc
 * @author Rémi Leclerc
 */
namespace Lyssal\Encryption;

use Lyssal\Encryption;

/**
 * Encryption with base64.
 */
class Base64 extends Encryption
{
    /**
     * Encode an URL in 64 bits.
     *
     * @return \Lyssal\Encryption\Base64 This
     */
    public function encodeUrl()
    {
        $this->string = rtrim(strtr(base64_encode($this->string), '+/', '-_'), '=');

        return $this;
    }

    /**
     * Decode an URL in 64 bits.
     *
     * @return \Lyssal\Encryption\Base64 This
     */
    public function decodeUrl()
    {
        $this->string = base64_decode(str_pad(strtr($this->string, '-_', '+/'), strlen($this->string) % 4, '=', STR_PAD_RIGHT));

        return $this;
    }
}
