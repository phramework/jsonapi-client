<?php
declare(strict_types=1);
/*
 * Copyright 2016 Xenofon Spafaridis
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
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Response\Collection;
use Phramework\JSONAPI\Client\Response\Errors;

trait Get
{
    /**
     * @param Directive[] ...$directives
     * @return Collection
     * @throws ResponseException
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

        $request = (new Request(Client::METHOD_GET, $url));

        //Add headers
        foreach ($this->headers as $header => $values) {
            $request = $request->withAddedHeader(
                $header,
                $values
            );
        }

        try {
            $response = $client->send($request);
        } catch (BadResponseException $exception) {
            throw new ResponseException(
                (new Errors($exception->getResponse()))
            );
        }

        return (new Collection($response));
    }
}