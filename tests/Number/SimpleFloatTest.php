<?php
use Lyssal\Number\SimpleFloat;

/**
 * Test de SimpleFloat.
 */
class SimpleFloatTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test minify().
     */
    public function testParse()
    {
        $float = SimpleFloat::parse('1.6');
        $this->assertEquals(1.6, $float->getFloat());

        $float = SimpleFloat::parse('2,12');
        $this->assertEquals(2.12, $float->getFloat());
    }
}
