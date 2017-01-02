<?php

namespace Phramework\JSONAPI\Client;

use Phramework\JSONAPI\Client\Directive\Directive;
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Response\Collection;
use Phramework\JSONAPI\Client\Response\JSONAPIResource;

/**
 * @since 2.0.0
 */
abstract class AbstractEndpoint
{
    /**
     * @param Directive[] ...$directives
     * @return Collection
     * @throws ResponseException
     */
    public abstract function get(
        Directive ...$directives
    ) : Collection;

    /**
     * @param string                $id
     * @param Directive[] ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     */
    public abstract function getById(
        string $id,
        Directive ...$directives
    ) : JSONAPIResource;

    /**
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive[]  ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     */
    public abstract function post(
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    );

    /**
     * @param string            $id Resource id
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive[]  ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     */
    public abstract function patch(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    );

    /**
     * @param string            $id Resource id
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive[]  ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     */
    public abstract function delete(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    );

    /**
     * @param string            $id Resource id
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive[]  ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     */
    public abstract function put(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    );
}
