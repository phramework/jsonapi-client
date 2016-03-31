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
namespace Phramework\JSONAPI\Client;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @coversDefaultClass \Phramework\JSONAPI\Client\RelationshipsData
 */
class RelationshipsDataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $relationshipData = (new RelationshipsData());

        $this->assertEquals(
            new \stdClass(),
            $relationshipData->getRelationships()
        );
    }

    /**
     * @covers ::getRelationships
     */
    public function testGetRelationships()
    {
        //Reuse test
        $this->testConstruct();
    }

    /**
     * @covers ::append
     */
    public function testAppend()
    {
        $relationshipData = (new RelationshipsData())
            ->append('group', '20');

        $expected = (object) [
            'group' => (object) [
                'data' => (object) [
                    'type' => 'group',
                    'id' => 20
                ]
            ]
        ];

        $this->assertEquals(
            $expected,
            $relationshipData->getRelationships()
        );
    }

    /**
     * @covers ::append
     */
    public function testAppendWithType()
    {
        $relationshipData = (new RelationshipsData())
            ->append('friend', ['20', '30'], 'user');

        $expected = (object) [
            'friend' => (object) [
                'data' => (object) [
                    'type' => 'user',
                    'id' => [20, 30]
                ]
            ]
        ];

        $this->assertEquals(
            $expected,
            $relationshipData->getRelationships()
        );
    }
}
