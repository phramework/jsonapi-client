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

require_once __DIR__ . '/BaseEndpoint.php';

use Phramework\JSONAPI\Client\Directive\IncludeRelationship;
use Phramework\JSONAPI\Client\Directive\Page;
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Response\JSONAPIResource;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @coversDefaultClass \Phramework\JSONAPI\Client\Endpoint
 */
class PostTest extends \PHPUnit_Framework_TestCase
{
    use BaseEndpoint;

    /**
     * @covers ::post
     */
    public function testPost()
    {
        $post = $this->endpoint->post(
            (object) [
                'title' => 'do this from phpunit',
                'body' => 'do this from phpunit - body',
            ]/*,
            (new RelationshipsData())
                ->append(
                    'tag',
                    '1'
                )
                ->append(
                    'tag',
                    '2'
                )*/
        );

        $this->markTestIncomplete();

        $this->assertInstanceOf(
            JSONAPIResource::class,
            $post
        );

        $this->assertSame(
            204,
            $post->getStatusCode()
        );
    }
}
