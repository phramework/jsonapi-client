<?php
declare(strict_types=1);

namespace src\Endpoint;

use PHPUnit\Framework\TestCase;
use Phramework\JSONAPI\Client\Endpoint;
use Phramework\JSONAPI\Client\Exceptions\ConnectException;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 2.6.0
 */
class ConnectTest extends TestCase
{
    /**
     * @expectedException \Phramework\JSONAPI\Client\Exceptions\ConnectException
     */
    public function testGetRequestWithWrongPortConnectExceptionIsThrown()
    {
        $endpoint = (new Endpoint('timeout'))
            ->setUrl('http://localhost:9999/wrongPort')
            ->withTimeout(2);

        $endpoint->get();
    }

    /**
     * @expectedException \Phramework\JSONAPI\Client\Exceptions\ConnectException
     */
    public function testPOSTRequestWithWrongPortConnectExceptionIsThrown()
    {
        $endpoint = (new Endpoint('timeout'))
            ->setUrl('http://localhost:9999/timeout')
            ->withTimeout(2);

        $endpoint->post();
    }

    /**
     * @expectedException \Phramework\JSONAPI\Client\Exceptions\ConnectException
     */
    public function testGetRequestWithWrongAddressConnectExceptionIsThrown()
    {
        $endpoint = (new Endpoint('timeout'))
            ->setUrl('http://172.16.0.0:9999/wrongPort')
            ->withTimeout(2);

        $endpoint->get();
    }
}
