<?php

namespace Phramework\JSONAPI\APP\Response;

use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Phramework\JSONAPI\Client\Response\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseTest extends TestCase
{
    private function getMockResponseInterface(string $responseBody): ResponseInterface {
        $responseStreamBody = \Mockery::mock(StreamInterface::class)
            ->shouldReceive('__toString')
            ->andReturn($responseBody)
            ->getMock()

            ->shouldReceive('getContents')
            ->andReturn($responseBody)
            ->getMock()

            ->shouldReceive('isSeekable')
            ->andReturn(false)
            ->getMock();

        /** @var ResponseInterface|MockInterface $responseMocked */
        $responseMocked = \Mockery::mock(ResponseInterface::class)
            ->shouldReceive('getBody')
            ->andReturn($responseStreamBody)
            ->getMock();

        return $responseMocked;
    }

    public function testConstruct__CorrectJson()
    {
        $givenCorrectResponse = '[{"a": "b"}]';

        $givenResponse = $this->getMockResponseInterface($givenCorrectResponse);

        $collection = new Collection($givenResponse);

        $this->assertTrue(true);
    }

    public function testConstruct__InvalidJson()
    {
        $givenCorrectResponse = '[{"a": "b"}';

        $givenResponse = $this->getMockResponseInterface($givenCorrectResponse);

        $this->expectException(\JsonException::class);

        $collection = new Collection($givenResponse);
    }
}
