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

use Phramework\JSONAPI\FilterAttribute;
use Phramework\JSONAPI\FilterJSONAttribute;
use Phramework\JSONAPI\Util;
use Phramework\Models\Operator;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Filter extends Directive
{
    protected $type;

    /**
     * @var string[]
     * @example
     * ```php
     * [1, 2]
     * ```
     */
    protected $primary = [];

    /**
     * @var \stdClass
     * @example
     * ```php
     * (object) [
     *     'author'  => [1],
     *     'comment' => [1, 2, 3],
     *     'tag'     => ['blog']
     * ]
     * ```
     */
    protected $relationships;

    /**
     * @var (FilterAttribute|FilterJSONAttribute)[]
     */
    protected $attributes = [];

    /**
     * @todo update example
     * Filter constructor.
     * @param string[] $primary
     * @param \stdClass $relationships null wil be interpreted as empty object
     * @param (FilterAttribute|FilterJSONAttribute)[] $filterAttributes
     * @throws \Exception
     * @throws \InvalidArgumentException
     * @example
     * ```php
     * $filter = new Filter(
     *     [1, 2],
     *     (object) [
     *         'author'  => [1],
     *         'comment' => [1, 2, 3],
     *         'tag'     => ['blog']
     *     ],
     *     [
     *         new FilterAttribute('title', Operator::LIKE, 'blog')
     *     ]
     * );
     * ```
     * @example
     * ```php
     * $filter = new Filter(
     *     [1, 2],
     *     null,
     *     [
     *         new FilterAttribute('title', Operator::LIKE, 'blog'),
     *         new FilterJSONAttribute('meta', 'keyword', Operator::EQUAL, 'blog')
     *     ]
     * );
     * ```
     */
    public function __construct(
        string $type,
        array $primary = [],
        \stdClass $relationships = null,
        array $filterAttributes = []
    ) {

        $this->type = $type;

        if ($relationships === null) {
            $relationships = new \stdClass();
        } elseif (is_array($relationships) && Util::isArrayAssoc($relationships)) { //Allow associative arrays
            $relationships = (object) $relationships;
        }
        if (!is_object($relationships)) {
            throw new \InvalidArgumentException(
                'Relationships filter must be an object'
            );
        }
        foreach ($relationships as $relationshipKey => $relationshipValue) {
            if (!is_array($relationshipValue) && $relationshipValue !== Operator::OPERATOR_EMPTY) {
                throw new \InvalidArgumentException(sprintf(
                    'Values for relationship filter "%s" MUST be an array or Operator::"%s"',
                    $relationshipKey,
                    Operator::OPERATOR_EMPTY
                ));
            }
        }

        /*if (!Util::isArrayOf($filterAttributes, FilterAttribute::class)) {
            throw new \InvalidArgumentException(
                'filterAttributes must be an array of FilterAttribute instances'
            );
        }*/

        $this->primary = $primary;
        $this->relationships = $relationships;
        $this->attributes = $filterAttributes;
    }

    /**
     * @return string
     */
    public function getURL(): string
    {
        $type = $this->type;

        $parts = [];

        //append primary filter
        if ($this->primary) {
            $parts[] = sprintf(
                'filter[%s]=%s',
                urlencode($type),
                implode(',', $this->primary)
            );
        }

        foreach ($this->relationships as $key => $value) {
            $parts[] = sprintf(
                'filter[%s]=%s',
                urlencode($key),
                implode(',', $value)
            );
        }

        foreach ($this->attributes as $attribute) {
            switch (get_class($attribute)) {
                case FilterAttribute::class:
                    $parts[] = sprintf(
                        'filter[%s]=%s%s',
                        urlencode($attribute->attribute),
                        urlencode($attribute->operator),
                        urlencode($attribute->operand)
                    );
                    break;
                case FilterJSONAttribute::class:
                    $parts[] = sprintf(
                        'filter[%s.%s]=%s%s',
                        urlencode($attribute->attribute),
                        urlencode($attribute->key),
                        urlencode($attribute->operator),
                        urlencode($attribute->operand)
                    );
                    break;
            }
        }

        return implode(
            '&',
            $parts
        );
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return \string[]
     */
    public function getPrimary(): array
    {
        return $this->primary;
    }

    /**
     * @return \stdClass
     */
    public function getRelationships(): \stdClass
    {
        return $this->relationships;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
