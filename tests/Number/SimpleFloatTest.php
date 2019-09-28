<?php
use Lyssal\Number\SimpleFloat;
use PHPUnit\Framework\TestCase;

/**
 * Test de SimpleFloat.
 */
class SimpleFloatTest extends TestCase
{
    /**
     * Test minify().
     */
    public function testParse()
    {
        $float = SimpleFloat::parse('1.6');
        $this->assertEquals(1.6, $float);

        $float = new SimpleFloat('2,12');
        $this->assertEquals(2.12, $float->getFloat());
    }
}
