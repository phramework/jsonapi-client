<?php
declare(strict_types=1);
/*
 * Copyright 2016-2017 Xenofon Spafaridis
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Phramework\JSONAPI\Client\Endpoint;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use Phramework\JSONAPI\Client\Client;
use Phramework\JSONAPI\Client\Directive\Directive;
use Phramework\JSONAPI\Client\Exceptions\ConnectException;
use Phramework\JSONAPI\Client\Exceptions\NetworkException;
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Exceptions\TimeoutException;
use Phramework\JSONAPI\Client\Response\Collection;
use Phramework\JSONAPI\Client\Response\Errors;
use Psr\Http\Message\ResponseInterface;

trait Get
{
    /**
     * @inheritDoc
     */
    public function get(
        Directive ...$directives
    ) : Collection {
        $url = $this->url;

        $questionMark = false;

        foreach ($directives as $directive) {
            $urlParsed = $directive->getURL();

            if (empty($urlParsed)) {
                continue;
            }

            $url = $url . ($questionMark ? '&' : '?') . $urlParsed;
            $questionMark = true;
        }

        $client = new \GuzzleHttp\Client([]);

        $request = new Request(Client::METHOD_GET, $url);

        //Add headers
        foreach ($this->headers as $header => $values) {
            $request = $request->withAddedHeader(
                $header,
                $values
            );
        }

        $response = $this->handleRequest($client, $request);

        return new Collection($response);
    }

    protected function handleRequest(
        $client,
        $request
    ): ResponseInterface {
        try {
            return $client->send($request, $this->getGuzzleOptions());
        } catch (\GuzzleHttp\Exception\ConnectException $exception) {
            if (isset($exception->getHandlerContext()['errno'])) {
                switch ($exception->getHandlerContext()['errno']) {
                    case 7:
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

    /**
     * @return array
     */
    protected function getGuzzleOptions(): array
    {
        $options = [
            'timeout' => (float) $this->timeout,
        ];
        return $options;
    }
}
