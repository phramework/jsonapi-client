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
namespace Phramework\JSONAPI\Client\Directive;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Page extends Directive
{
    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int|null
     */
    protected $limit;

    /**
     * @param int $limit
     * @param int $offset
     */
    public function __construct($limit = null, $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function getURL(): string
    {
        $parts = [];

        //append limit
        if ($this->limit) {
            $parts[] = sprintf(
                'page[limit]=%s',
                $this->limit
            );
        }

        //append offset
        if ($this->offset) {
            $parts[] = sprintf(
                'page[offset]=%s',
                $this->offset
            );
        }

        return implode(
            '&',
            $parts
        );
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }
}