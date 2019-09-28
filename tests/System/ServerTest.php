<?php
use Lyssal\System\Server;
use PHPUnit\Framework\TestCase;

/**
 * Test de System.
 */
class SystemTest extends TestCase
{
    /**
     * Test getPhpSizeInBytes().
     */
    public function testPhpSize()
    {
        $this->assertEquals(Server::getPhpSizeInBytes('123'), 123);
        $this->assertEquals(Server::getPhpSizeInBytes('12K'), 12 * 1024);
        $this->assertEquals(Server::getPhpSizeInBytes('3M'), 3 * 1024 * 1024);
        $this->assertEquals(Server::getPhpSizeInBytes('309G'), 309 * 1024 * 1024 * 1024);
        $this->assertEquals(Server::getPhpSizeInBytes('42T'), 42 * 1024 * 1024 * 1024 * 1024);
        $this->assertEquals(Server::getPhpSizeInBytes('42P'), 42 * 1024 * 1024 * 1024 * 1024 * 1024);
    }
}
