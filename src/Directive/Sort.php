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

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Sort extends Directive
{
    /**
     * @var bool
     */
    protected $ascending;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @param string $attribute
     * @param bool $ascending
     */
    public function __construct(
        string $attribute,
        bool $ascending = true
    ) {
        $this->attribute = $attribute;
        $this->ascending = $ascending;
    }

    /**
     * @return string
     */
    public function getURL(): string
    {
        return sprintf(
            'sort=%s%s',
            ($this->ascending ? '' : '-'),
            $this->attribute
        );
    }

    /**
     * @return boolean
     */
    public function getAscending(): bool
    {
        return $this->ascending;
    }

    /**
     * @return string
     */
    public function getAttribute(): string
    {
        return $this->attribute;
    }
}
