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

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\BadResponseException;
use Phramework\JSONAPI\Client\Directive\Directive;
use Phramework\JSONAPI\Client\Endpoint\Get;
use Phramework\JSONAPI\Client\Endpoint\GetById;
use Phramework\JSONAPI\Client\Endpoint\Post;
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Response\Collection;
use Phramework\JSONAPI\Client\Response\Errors;
use Phramework\JSONAPI\Client\Response\JSONAPIResource;

/**
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 3.0.0
 */
class Endpoint extends AbstractEndpointWithPostWithId
{
    use Get;
    use GetById;
    use Post;

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
     * @var int Request timeout in seconds
     * @since 2.6.0
     */
    protected $timeout;

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

        $this->timeout = 60;
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
     * @param string $type
     * @return Endpoint
     */
    public function setType(string $type) : Endpoint
    {
        $this->type = $type;

        return $this;
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
     * @return int Request timeout in seconds
     * @since 2.6.0
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return $this
     * @since 2.6.0
     */
    public function withTimeout(int $timeout): AbstractEndpointWithPostWithId
    {
        $clone = clone $this;
        $clone->timeout = $timeout;

        return $clone;
    }

    public function __clone()
    {
        $this->headers = clone $this->headers;
    }
}
