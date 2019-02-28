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

use PHPUnit\Framework\TestCase;
use Phramework\JSONAPI\APP\BaseEndpoint;
use Phramework\JSONAPI\Client\Directive\IncludeRelationship;
use Phramework\JSONAPI\Client\Directive\Page;
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Response\JSONAPIResource;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @coversDefaultClass \Phramework\JSONAPI\Client\Endpoint
 * @todo use phramework/jsonapi to server an actual API
 */
class GetByIdTest extends TestCase
{
    use BaseEndpoint;

    /**
     * @covers ::getById
     */
    public function testGeyById()
    {
        $id = $this->get();

        $r = $this->endpoint->getById($id);

        $this->assertSame(
            $this->resourceType,
            $r->getData()->type
        );
    }
}
