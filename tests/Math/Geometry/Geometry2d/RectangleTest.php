<?php
use Lyssal\Math\Geometry\Geometry2d\Rectangle;

/**
 * Test de Rectangle.
 */
class RectangleTest
{
    /**
     * Test constructor.
     */
    public function testConstructor()
    {
        $rectangle = new Rectangle(2, 3, 4, 5);
        $this->assertEquals($rectangle->getX(), 2);
        $this->assertEquals($rectangle->getLocation()->getX(), 2);
        $this->assertEquals($rectangle->getY(), 3);
        $this->assertEquals($rectangle->getLocation()->getY(), 3);
        $this->assertEquals($rectangle->getWidth(), 4);
        $this->assertEquals($rectangle->getDimension()->getWidth(), 4);
        $this->assertEquals($rectangle->getHeight(), 5);
        $this->assertEquals($rectangle->getDimension()->getHeight(), 5);
    }
}
