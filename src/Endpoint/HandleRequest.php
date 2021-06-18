<?php

namespace Phramework\JSONAPI\Client\Endpoint;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Phramework\JSONAPI\Client\Exceptions\ConnectException;
use Phramework\JSONAPI\Client\Exceptions\NetworkException;
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Exceptions\TimeoutException;
use Phramework\JSONAPI\Client\Response\Errors;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HandleRequest
{
    public static function handleRequest(
        Client $client,
        RequestInterface $request
    ): ResponseInterface {
        try {
            return $client->send($request, []);
        } catch (\GuzzleHttp\Exception\ConnectException $exception) {
            if (isset($exception->getHandlerContext()['errno'])) {
                switch ($exception->getHandlerContext()['errno']) {
                    case 5: //CURLE_COULDNT_RESOLVE_PROXY
                    case 6: //CURLE_COULDNT_RESOLVE_HOST
                    case 7: //CURLE_COULDNT_CONNECT
                    case 16: //CURLE_HTTP2
                    case 35: //CURLE_SSL_CONNECT_ERROR
                        throw new ConnectException($exception);
                    case 28:
                        throw new TimeoutException($exception);
                }
            }

            throw new NetworkException($exception);
        } catch (BadResponseException $exception) {
            throw new ResponseException(
                (new Errors($exception->getResponse()))
            );
        }
    }
}
