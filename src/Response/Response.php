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
namespace Phramework\JSONAPI\Client\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
abstract class Response
{
    /**
     * @var \stdClass
     */
    protected $meta;
    
    /**
     * @var \stdClass
     */
    protected $links;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Parse response object, will copy any top members available at this
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;

        $body = json_decode($response->getBody()->getContents());

        if ($body) {
            
            $members = array_keys(get_object_vars($this));

            foreach ($members as $member) {
                if (isset($body->{$member})) {
                    $this->{$member} = $body->{$member};
                }
            }
        }
    }

    /**
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return string[][]
     */
    public function getHeaders() : array
    {
        return $this->response->getHeaders();
    }

    /**
     * @return \stdClass
     */
    public function getMeta(): \stdClass
    {
        return $this->meta;
    }
    /**
     * @return \stdClass
     */
    public function getLinks(): \stdClass
    {
        return $this->links;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
