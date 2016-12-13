<?php
use Lyssal\Http\Status;
use Lyssal\Url;

/**
 * Test de Url.
 */
class UrlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string Google
     */
    const URL_GOOGLE = 'http://www.google.fr/';

    /**
     * Test getUrl().
     */
    public function testGetUrl()
    {
        $url = new Url(self::URL_GOOGLE);

        $this->assertEquals(self::URL_GOOGLE, $url->getUrl());
    }

    /**
     * Test getStatusCode().
     */
    public function testStatusCode()
    {
        $url = new Url(self::URL_GOOGLE);

        $this->assertEquals(Status::OK, $url->getStatusCode());
    }

    /**
     * Test statusIsSuccess().
     */
    public function testStatusIsSuccess()
    {
        $url = new Url(self::URL_GOOGLE);

        $this->assertTrue($url->statusIsSuccess());
    }

    /**
     * Test exists().
     */
    public function testExists()
    {
        $url = new Url(self::URL_GOOGLE);

        $this->assertTrue($url->exists());
    }
}
