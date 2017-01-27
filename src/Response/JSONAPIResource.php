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
namespace Phramework\JSONAPI\Client\Response;

use Phramework\JSONAPI\Client\ResourceObject;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 * @link http://jsonapi.org/format/#document-resource-objects
 * @property-read string $id
 * @property-read string $type
 * @property-read \stdClass $attributes
 * @property-read \stdClass $relationships
 */
class JSONAPIResource extends Response
{
    /**
     * A single resource object, a single resource identifier object, or null
     * @var ResourceObject
     */
    protected $data = null;

    /**
     * Compound Documents
     * @var ResourceObject[]
     * @link http://jsonapi.org/format/#document-compound-documents
     */
    protected $included;

    /**
     * @return ResourceObject
     */
    public function getData()
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

    public function __get($name)
    {
        $members = array_keys(get_object_vars($this));

        if (!in_array($name, $members) && isset($this->data->{$name})) {
            return $this->data->{$name};
        }

        throw new \Exception('Invalid property ' . $name);
    }
}
