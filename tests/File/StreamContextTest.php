<?php
use Lyssal\File\StreamContext;

/**
 * Test de StreamContext.
 */
class StreamContextTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test StreamContext.
     */
    public function testStreamContext()
    {
        $streamContext = new StreamContext(array(
            'http' => array(
                'method' => 'GET'
            )
        ));
        $streamContext->addOption('http.proxy', 'tcp://0.0.0.0');
        $streamContext->addOptions(array(
            'http' => array(
                'timeout' => 1
            ),
            'ssl' => array(
                'verify_peer' => true
            )
        ));

        $resultOptions = array(
            'http' => array(
                'method' => 'GET',
                'proxy' => 'tcp://0.0.0.0',
                'timeout' => 1
            ),
            'ssl' => array(
                'verify_peer' => true
            )
        );

        $this->assertEquals($streamContext->getOptions(), $resultOptions);
    }
}
