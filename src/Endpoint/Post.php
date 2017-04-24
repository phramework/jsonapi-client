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
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\RelationshipsData;
use Phramework\JSONAPI\Client\Response\Errors;
use Phramework\JSONAPI\Client\Response\JSONAPIResource;

/**
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 3.0.0
 */
trait Post
{
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
        return $this->withPayload(
            Client::METHOD_POST,
            '',
            $attributes,
            $relationships,
            null,
            ...$directives
        );
    }

    /**
     * @param string            $id Resource id
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive[]  ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     */
    public function patch(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    ) {
        return $this->withPayload(
            Client::METHOD_PATCH,
            $id . '/',
            $attributes,
            $relationships,
            $id,
            ...$directives
        );
    }

    /**
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive[]  ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     */
    public function put(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    ) {
        return $this->withPayload(
            Client::METHOD_PUT,
            $id . '/',
            $attributes,
            $relationships,
            $id,
            ...$directives
        );
    }

    /**
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive[]  ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     */
    public function delete(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    ) {
        return $this->withPayload(
            Client::METHOD_DELETE,
            $id . '/',
            $attributes,
            $relationships,
            $id,
            ...$directives
        );
    }

    /**
     *
     * @param string            $method
     * @param string            $urlSuffix
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive[]  ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     */
    protected function withPayload(
        string $method,
        string $urlSuffix = '',
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        string $id = null,
        Directive ...$directives
    ) {
        $url = $this->url . $urlSuffix;

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

        if ($id !== null) {
            $body->data->id = $id;
        }

        if ($attributes !== null) {
            $body->data->attributes = $attributes;
        }

        if ($relationships !== null) {
            $body->data->relationships = $relationships->getRelationships();
        }

        $client = new \GuzzleHttp\Client([]);

        $request = (new Request(
            $method,
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

        try {
            $response = $client->send($request);
        } catch (BadResponseException $exception) {
            throw new ResponseException(
                (new Errors($exception->getResponse()))
            );
        }

        return (new JSONAPIResource($response));
    }
}
