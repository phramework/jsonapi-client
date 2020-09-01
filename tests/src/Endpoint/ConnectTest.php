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
    public function testGetRequestWithWrongPortConnectExceptionIsThrown()
    {
        $endpoint = (new Endpoint('any'))
            ->setUrl('http://localhost:9999/wrongPort');

        $this->expectException(ConnectException::class);

        $endpoint->get();
    }

    public function testPOSTRequestWithWrongPortConnectExceptionIsThrown()
    {
        $endpoint = (new Endpoint('any'))
            ->setUrl('http://localhost:9999/timeout');

        $this->expectException(ConnectException::class);

        $endpoint->post();
    }

    public function testGetRequestWithWrongAddressConnectExceptionIsThrown()
    {
        $endpoint = (new Endpoint('any'))
            ->setUrl('http://127.0.0.2:9999/wrongPort');

        $this->expectException(ConnectException::class);

        $endpoint->get();
    }

    public function testGetRequestWithWrongDomainConnectExceptionIsThrown()
    {
        $endpoint = (new Endpoint('any'))
            ->setUrl('https://wrongDomain.see/');

        $this->expectException(ConnectException::class);

        $endpoint->get();
    }
}
