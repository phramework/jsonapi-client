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

use Phramework\JSONAPI\Client\ResourceObject;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Resource extends Response
{

    /**
     * @var ResourceObject
     */
    public $data;

    /**
     * @var ResourceObject[]
     */
    public $included;

   /**
     * Collection constructor.
     * @param ResourceObject[] $data
     * @param ResourceObject[] $included
     */
    /* public function __construct(
        $data = null,
        array $included = null,
        $links = null,
        $meta = null
    ) {
        $this->data = $data;
        $this->included = $included;

        parent::__construct($meta, $links);
    }*/
}