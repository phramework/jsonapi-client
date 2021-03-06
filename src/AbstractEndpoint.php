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
namespace Phramework\JSONAPI\Client;

use Phramework\JSONAPI\Client\Directive\Directive;
use Phramework\JSONAPI\Client\Exceptions\NetworkException;
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Exceptions\TimeoutException;
use Phramework\JSONAPI\Client\Response\Collection;
use Phramework\JSONAPI\Client\Response\JSONAPIResource;

/**
 * @since 2.0.0
 */
abstract class AbstractEndpoint
{
    /**
     * @param Directive ...$directives
     * @return Collection
     * @throws ResponseException
     * @throws TimeoutException
     * @throws NetworkException
     */
    abstract public function get(
        Directive ...$directives
    ) : Collection;

    /**
     * @param string                $id
     * @param Directive ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     * @throws TimeoutException
     * @throws NetworkException
     */
    abstract public function getById(
        string $id,
        Directive ...$directives
    ) : JSONAPIResource;

    /**
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     * @throws TimeoutException
     * @throws NetworkException
     */
    abstract public function post(
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    );

    /**
     * @param string            $id Resource id
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     * @throws TimeoutException
     * @throws NetworkException
     */
    abstract public function patch(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    );

    /**
     * @param string            $id Resource id
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     * @throws TimeoutException
     * @throws NetworkException
     */
    abstract public function delete(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    );

    /**
     * @param string            $id Resource id
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     * @throws TimeoutException
     * @throws NetworkException
     */
    abstract public function put(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    );
}
