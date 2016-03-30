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

use Phramework\JSONAPI\Client\APP\User;
use Phramework\JSONAPI\FilterAttribute;
use Phramework\Models\Operator;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 * @coversDefaultClass \Phramework\JSONAPI\Client\Client
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::get
     */
    public function testGet()
    {
        $users = User::get(
            new Page(1, 10)
        );
            /*new Filter(
                [1, 2, 3],
                null,
                [
                    new FilterAttribute('language', Operator::OPERATOR_EQUAL, 'en')
                ]
            ),
            new Sort(null, 'created', false),
            new Fields((object) [
                'user' => ['name', 'email']
            ]),
            new IncludeRelationship('project', 'group')
        );*/

        $userId = $users->data[0]->id;

        var_dump($users);

        return $userId;
    }

    /**
     * @param string $userId
     * @covers ::get
     * @depends  testGet
     */
    public function testGetById($userId)
    {
        $user = User::getById(
            $userId
        );

        var_dump($user);
    }
}
