<?php
use Lyssal\Text\SimpleString;

/**
 * Test de SimpleString.
 */
class SimpleStringTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string String example
     */
    const STRING_EXAMPLE = 'Les "éléphants" sont à l\'abri.';


    /**
     * Test minify().
     */
    public function testMinify()
    {
        $string = new SimpleString(self::STRING_EXAMPLE);
        $string->minify('', true);
        $this->assertEquals('leselephantssontalabri', $string->getText());

        $string = new SimpleString(self::STRING_EXAMPLE);
        $string->minify('*', false);
        $this->assertEquals('Les*elephants*sont*a*l*abri', $string->getText());
    }

    /**
     * Test hasLetter().
     */
    public function testHasLetter()
    {
        $string = new SimpleString(self::STRING_EXAMPLE);
        $this->assertTrue($string->hasLetter());

        $string = new SimpleString('1\'23%:!');
        $this->assertFalse($string->hasLetter());

        $string = new SimpleString('1\'23ë%:!');
        $this->assertTrue($string->hasLetter());
    }

    /**
     * Test hasDigit().
     */
    public function testHasDigit()
    {
        $string = new SimpleString(self::STRING_EXAMPLE);
        $this->assertFalse($string->hasDigit());

        $string = new SimpleString('123456789');
        $this->assertTrue($string->hasDigit());

        $string = new SimpleString('123er');
        $this->assertTrue($string->hasDigit());
    }

    /**
     * Test hasDigit().
     */
    public function testBn2ln()
    {
        $assezTot = "Assez\ntôt";

        $string = new SimpleString($assezTot);
        $string->br2nl();
        $this->assertEquals($assezTot, $string->getText());

        $string = new SimpleString("Assez<br>tôt");
        $string->br2nl();
        $this->assertEquals($assezTot, $string->getText());

        $string = new SimpleString("Assez<br/>tôt");
        $string->br2nl();
        $this->assertEquals($assezTot, $string->getText());

        $string = new SimpleString("Assez<br />tôt");
        $string->br2nl();
        $this->assertEquals($assezTot, $string->getText());

        $string = new SimpleString("Assez<br   />tôt");
        $string->br2nl();
        $this->assertEquals($assezTot, $string->getText());
    }
}
