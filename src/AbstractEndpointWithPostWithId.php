<?php
declare(strict_types=1);

namespace Phramework\JSONAPI\Client;

use Phramework\JSONAPI\Client\Directive\Directive;
use Phramework\JSONAPI\Client\Exceptions\NetworkException;
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Exceptions\TimeoutException;
use Phramework\JSONAPI\Client\Response\JSONAPIResource;

/**
 * @author Xenofon Spafaridis <xspafaridis@vivantehealth.com>
 * @since 2.5.0
 */
abstract class AbstractEndpointWithPostWithId extends AbstractEndpoint
{
    /**
     * @param \stdClass         $attributes
     * @param RelationshipsData $relationships
     * @param Directive  ...$directives
     * @return JSONAPIResource
     * @throws ResponseException
     * @throws TimeoutException
     * @throws NetworkException
     */
    abstract public function postWithId(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        Directive ...$directives
    );
}
