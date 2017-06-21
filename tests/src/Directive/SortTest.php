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
namespace Phramework\JSONAPI\Client\Directive;

use PHPUnit\Framework\TestCase;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @coversDefaultClass \Phramework\JSONAPI\Client\Directive\Sort
 */
class SortTest extends TestCase
{
    /**
     * @covers ::getURL
     */
    public function testToURL()
    {
        $this->assertSame(
            'sort=created',
            (new Sort('created'))
                ->getURL()
        );

        $this->assertSame(
            'sort=-created',
            (new Sort('created', false))
                ->getURL()
        );
    }
}
