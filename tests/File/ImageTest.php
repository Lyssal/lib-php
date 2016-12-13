<?php
use Lyssal\File\Image;

/**
 * Test de Image.
 */
class ImageTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test Image.
     */
    public function testImage()
    {
        $image = new Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'test.png');
        $this->assertEquals($image->getWidth(), 128);
        $this->assertEquals($image->getHeight(), 128);

        $image2 = $image->copy(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'test2.png');
        $image2->resize(30, 40);

        $image3 = new Image(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'test2.png');
        $this->assertEquals($image3->getWidth(), 30);
        $this->assertEquals($image3->getHeight(), 40);
        $image3->delete();
    }
}
