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
        $endpoint = (new Endpoint('any'))
            ->setUrl('http://localhost:9999/wrongPort');

        $endpoint->get();
    }

    /**
     * @expectedException \Phramework\JSONAPI\Client\Exceptions\ConnectException
     */
    public function testPOSTRequestWithWrongPortConnectExceptionIsThrown()
    {
        $endpoint = (new Endpoint('any'))
            ->setUrl('http://localhost:9999/timeout');

        $endpoint->post();
    }

    /**
     * @expectedException \Phramework\JSONAPI\Client\Exceptions\ConnectException
     */
    public function testGetRequestWithWrongAddressConnectExceptionIsThrown()
    {
        $endpoint = (new Endpoint('any'))
            ->setUrl('http://127.0.0.2:9999/wrongPort');

        $endpoint->get();
    }

    /**
     * @expectedException \Phramework\JSONAPI\Client\Exceptions\ConnectException
     */
    public function testGetRequestWithWrongDomainConnectExceptionIsThrown()
    {
        $endpoint = (new Endpoint('any'))
            ->setUrl('https://wrongDomain.see/');

        $endpoint->get();
    }
}
