<?php
use Lyssal\System;

/**
 * Test de System.
 */
class SystemTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test getPhpSizeInBytes().
     */
    public function testPhpSize()
    {
        $this->assertEquals(System::getPhpSizeInBytes('123'), 123);
        $this->assertEquals(System::getPhpSizeInBytes('12K'), 12 * 1024);
        $this->assertEquals(System::getPhpSizeInBytes('3M'), 3 * 1024 * 1024);
        $this->assertEquals(System::getPhpSizeInBytes('309G'), 309 * 1024 * 1024 * 1024);
        $this->assertEquals(System::getPhpSizeInBytes('42T'), 42 * 1024 * 1024 * 1024 * 1024);
        $this->assertEquals(System::getPhpSizeInBytes('42P'), 42 * 1024 * 1024 * 1024 * 1024 * 1024);
    }
}
