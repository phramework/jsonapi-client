<?php
declare(strict_types=1);

namespace src\Endpoint;

use PHPUnit\Framework\TestCase;
use Phramework\JSONAPI\Client\Endpoint;
use Phramework\JSONAPI\Client\Exceptions\TimeoutException;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 2.6.0
 */
class TimeoutTest extends TestCase
{
    /**
     * @expectedException \Phramework\JSONAPI\Client\Exceptions\TimeoutException
     */
    public function testGetRequestTimeoutExceptionIsThrown()
    {
        $endpoint = (new Endpoint('timeout'))
            ->setUrl('http://localhost:8005/timeout')
            ->withTimeout(2);

        $endpoint->get();
    }

    /**
     * @expectedException \Phramework\JSONAPI\Client\Exceptions\TimeoutException
     */
    public function testPOSTRequestTimeoutExceptionIsThrown()
    {
        $endpoint = (new Endpoint('timeout'))
            ->setUrl('http://localhost:8005/timeout')
            ->withTimeout(2);

        $endpoint->post();
    }
}
