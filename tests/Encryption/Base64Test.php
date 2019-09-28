<?php
use Lyssal\Encryption\Base64;
use PHPUnit\Framework\TestCase;

/**
 * Test de Html.
 */
class Base64Test extends TestCase
{
    /**
     * Test urlEncode() ans urlDecode().
     */
    public function testUrlXcode()
    {
        $urlString = 'toto#titi@tutu+tata\\=tàtà tyty$¨^âïe("';

        $string = new Base64($urlString);
        $string->encodeUrl()->decodeUrl();
        $this->assertEquals($string->getString(), $urlString);
    }
}
