<?php
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
namespace Phramework\JSONAPI\Client\Response;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
abstract class Response
{
    /**
     * @var \stdClass
     */
    public $meta;
    
    /**
     * @var \stdClass
     */
    public $links;

    /**
     * @var integer
     */
    protected $statusCode;

    /**
     * @var \stdClass
     */
    protected $headers;

    /**
     * Parse response object, will copy any top members available at this
     * Response instance
     * @param \stdClass $response
     * @return $this
     */
    public function parse(\stdClass $response)
    {
        $members = array_keys(get_object_vars($this));

        foreach ($members as $member) {
            $this->{$member} = $response->{$member} ?? null;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param \stdClass $headers
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }
}
