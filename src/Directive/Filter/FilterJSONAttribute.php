<?php
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
namespace Phramework\JSONAPI\Client\Directive\Filter;

/**
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 2.4.1
 */
class FilterJSONAttribute extends FilterAttribute
{
    /**
     * @var string
     */
    protected $key;

    /**
     * FilterAttribute constructor.
     * @param string $attribute
     * @param string $key
     * @param string $operator
     * @param string $operand
     */
    public function __construct(
        string $attribute,
        string $key,
        string $operator,
        $operand
    ) {
        parent::__construct(
            $attribute,
            $operator,
            $operand
        );

        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey() : string
    {
        return $this->key;
    }
}
