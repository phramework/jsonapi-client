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

use Phramework\JSONAPI\Client\ResourceObject;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Collection extends Response implements \ArrayAccess
{
    /**
     * An array of resource objects, an array of resource identifier objects,
     * or an empty array
     * @var ResourceObject[]
     */
    protected $data = [];

    /**
     * Compound Documents
     * @var ResourceObject[]
     * @link http://jsonapi.org/format/#document-compound-documents
     */
    protected $included;

    /**
     * @return \Phramework\JSONAPI\Client\ResourceObject[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return \Phramework\JSONAPI\Client\ResourceObject[]
     */
    public function getIncluded(): array
    {
        return $this->included;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * @param mixed $offset
     * @return ResourceObject|null
     */
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
}