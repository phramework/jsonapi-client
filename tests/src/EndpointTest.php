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
namespace Phramework\JSONAPI\Client;

use Phramework\JSONAPI\Client\Directive\IncludeRelationship;
use Phramework\JSONAPI\Client\Directive\Page;
use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Response\JSONAPIResource;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @coversDefaultClass \Phramework\JSONAPI\Client\Endpoint
 * @todo use phramework/jsonapi to server an actual API
 */
class EndpointTest extends \PHPUnit_Framework_TestCase
{
    protected $resourceType;

    /**
     * @var Endpoint
     */
    protected $endpoint;

    public function setUp()
    {
        $this->resourceType = 'article';

        $this->endpoint = (new Endpoint($this->resourceType))
            ->setUrl('http://localhost:8005/' . $this->resourceType);
    }

    /**
     * @covers ::get
     * @return string
     */
    public function testGet()
    {
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


    /**
     * @covers ::get
     * @expectedException \Exception
     */
    public function testGetNotFoundServer()
    {
        $endpoint = (new Endpoint('not-found'))
            ->setUrl('http://404-not-found-server.com/resource');

        $collection = $endpoint->get();

        $this->markTestIncomplete();
    }

    /**
     * @covers ::get
     * @expectedException \Exception
     */
    public function testGetNotFoundResource()
    {
        $id = (string) 2**31; //Very large resource id, probably is going to missing

        $resource = $this->endpoint->getById($id);

        $this->markTestIncomplete();
    }

    /**
     * @covers ::get
     */
    public function testGetNotFoundResourceDetails()
    {
        $id = (string) 2**31; //Very large resource id, probably is going to missing

        try {
            $resource = $this->endpoint->getById($id);
        } catch (ResponseException $e) {
            $this->assertSame(
                'Resource not found',
                $e->getErrors()[0]->title
            );

            $this->assertSame(
                '404',
                $e->getErrors()[0]->status
            );
        }
    }

    /**
     * @param string $id
     * @covers ::getById
     * @depends testGet
     */
    public function testGeyById(string $id)
    {
        $r = $this->endpoint->getById($id);

        $this->assertSame(
            $this->resourceType,
            $r->getData()->type
        );
    }

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
