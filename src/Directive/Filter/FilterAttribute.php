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
class FilterAttribute
{
    /**
     * @var string
     */
    protected $attribute;
    /**
     * @var string
     */
    protected $operator;
    /**
     * @var mixed|null
     */
    protected $operand;

    /**
     * FilterAttribute constructor.
     * @param string      $attribute
     * @param string      $operator
     * @param mixed|null $operand
     */
    public function __construct(
        string $attribute,
        string $operator,
        $operand = null
    ) {
        $this->attribute = $attribute;
        $this->operator = $operator;
        $this->operand = $operand;
    }

    /**
     * @return string
     */
    public function getAttribute() : string
    {
        return $this->attribute;
    }

    /**
     * @return string
     */
    public function getOperator() : string
    {
        return $this->operator;
    }

    /**
     * @return mixed|null
     */
    public function getOperand()
    {
        return $this->operand;
    }
}
