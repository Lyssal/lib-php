<?php
use Lyssal\Color\Color;
use PHPUnit\Framework\TestCase;

/**
 * Test de Color.
 */
class ColorTest extends TestCase
{
    /**
     * Test new Color().
     */
    public function testColor()
    {
        $color = new Color('#42ae81');
        $this->assertEquals($color->getHexadecimalColor(), '#42AE81');
        $this->assertEquals($color->getRed(), 66);
        $this->assertEquals($color->getGreen(), 174);
        $this->assertEquals($color->getBlue(), 129);

        $color = new Color(66, 174, 129);
        $this->assertEquals($color->getHexadecimalColor(), '#42AE81');
        $this->assertEquals($color->getRed(), 66);
        $this->assertEquals($color->getGreen(), 174);
        $this->assertEquals($color->getBlue(), 129);
    }
}
