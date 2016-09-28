<?php
declare(strict_types=1);

/**
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
namespace Phramework\JSONAPI\Client;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\BadResponseException;
use Phramework\JSONAPI\Client\Directive\Directive;
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Response\Collection;
use Phramework\JSONAPI\Client\Response\Errors;
use Phramework\JSONAPI\Client\Response\JSONAPIResource;

/**
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 3.0.0
 */
class Endpoint
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \stdClass
     */
    protected $headers;

    /**
     * Will initialize headers with
     * - Content-Type: application/vnd.api+json
     * - Accept: application/vnd.api+json
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->headers = new \stdClass();

        $this->type = $type;

        $this->withAddedHeader(
            'Content-Type',
            'application/vnd.api+json'
        );

        $this->withAddedHeader(
            'Accept',
            'application/vnd.api+json'
        );
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $url
     * @return Endpoint
     */
    public function setUrl(string $url): Endpoint
    {
        $url = rtrim($url, '/') . '/';

        $this->url = $url;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getHeaders() : \stdClass
    {
        return $this->headers;
    }

    /**
     * Overwrite header value
     * @param string    $header
     * @param \string[] ...$values
     * @return $this
     */
    public function withHeader(string $header, string ...$values) : Endpoint
    {
        $this->headers->{$header} = $values;


        return $this;
    }

    /**
     * Append header value
     * @param string    $header
     * @param \string[] ...$values
     * @return $this
     */
    public function withAddedHeader(string $header, string ...$values) : Endpoint
    {
        if (isset($this->headers->{$header})) {
            $this->headers->{$header} = array_merge(
                $this->headers->{$header},
                $values
            );
        } else {
            $this->headers->{$header} = $values;
        }

        return $this;
    }

    /**
     * @param $header
     * @return $this
     */
    public function withoutHeader($header) : Endpoint
    {
        unset($this->headers->{$header});

        return $this;
    }

    /**
     * @param Directive[] ...$directives
     * @return Collection
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

        $response = $client->send($request);

        $responseStatusCode = $response->getStatusCode();

        if (!in_array($responseStatusCode, [200])) {
            throw new ResponseException(
                (new Errors($response))
            );
        }

        return (new Collection($response));
    }

    /**
     * @param string                $id
     * @param Directive[] ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     * @todo prevent //
     */
    public function getById(
        string $id,
        Directive ...$directives
    ) : JSONAPIResource {

        $url = $this->url . $id . '/';

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

        $responseStatusCode = $response->getStatusCode();

        if (!in_array($responseStatusCode, [200])) {
            throw new ResponseException(
                (new Errors($response))
            );
        }

        return (new JSONAPIResource($response));
    }

    /**
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive[]  ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     */
    public function post(
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    ) {
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

        //prepare request body

        $body = (object) [
            'data' => (object) [
                'type' => $this->type
            ]
        ];

        if ($attributes !== null) {
            $body->data->attributes = $attributes;
        }

        if ($relationships !== null) {
            $body->data->relationships = $relationships->getRelationships();
        }

        $client = new \GuzzleHttp\Client([]);

        $request = (new Request(
            Client::METHOD_POST,
            $url,
            [],
            json_encode($body)
        ));

        //Add headers
        foreach ($this->headers as $header => $values) {
            $request = $request->withAddedHeader(
                $header,
                $values
            );
        }

        $response = $client->send($request);

        $responseStatusCode = $response->getStatusCode();

        if (!in_array($responseStatusCode, [200, 201, 202, 204])) {
            throw new ResponseException(
                (new Errors($response))
            );
        }

        return (new JSONAPIResource($response));
    }

    /**
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive[]  ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     * @todo
     */
    public function patch(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    ) {

    }
}
