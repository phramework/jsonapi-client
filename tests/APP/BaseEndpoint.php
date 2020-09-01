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
namespace Phramework\JSONAPI\APP;

use Phramework\JSONAPI\Client\Directive\IncludeRelationship;
use Phramework\JSONAPI\Client\Directive\Page;
use Phramework\JSONAPI\Client\Endpoint;

trait BaseEndpoint
{
    protected $resourceType;

    /**
     * @var Endpoint
     */
    protected $endpoint;

    public function setUp(): void
    {
        $this->resourceType = 'article';

        $this->endpoint = (new Endpoint($this->resourceType))
            ->setUrl('http://localhost:8005/' . $this->resourceType);
    }

    /**
     * @return string
     */
    public function get()
    {
        $this->setUp();

        $collection = $this->endpoint->get(
            new IncludeRelationship('author'),
            new Page(2)
        );

        $this->assertSame(
            200,
            $collection->getStatusCode()
        );

        $this->assertInternalType(
            'array',
            $collection->getData()
        );

        $data = $collection->getData();

        $this->assertLessThanOrEqual(
            2,
            count($data)
        );

        $id = $collection->getData()[0]->id;
        $id = $collection[0]->id;

        $this->assertInternalType(
            'string',
            $id
        );

        $this->assertSame(
            $collection->getData()[0]->id,
            $collection[0]->id,
            'Expect both notations to return same id'
        );

        return $id;
    }
}
