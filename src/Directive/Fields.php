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

use Phramework\JSONAPI\Client\Directive\Directive;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Fields extends Directive
{
    /**
     * @var \stdClass
     */
    protected $fields;

    /**
     * @param \stdClass $fields
     * @throws \Exception
     * @example
     * ```php
     * new Fields((object)
     *     Article::getType() => ['title']
     * ]);
     * ```
     * @example
     * ```php
     * new Fields((object)
     *     Article::getType() => ['title', 'updated'],
     *     Tag::getType()     => ['title']
     * ]);
     * ```
     */
    public function __construct($fields = null)
    {
        if ($fields === null) {
            $this->fields = new \stdClass();
        } else {
            foreach ($fields as $resourceType => $field) {
                if (!is_array($field)) {
                    throw new \Exception(sprintf(
                        'Resource type "%s" fields value expected to be an array',
                        $resourceType
                    ));
                }
            }
            $this->fields = $fields;
        }
    }

    /**
     * @return string
     */
    public function getURL(): string
    {
        $parts = [];

        foreach ($this->fields as $key => $value) {
            $parts[] = sprintf(
                'fields[%s]=%s',
                $key,
                implode(
                    ',',
                    array_map(
                        'urlencode',
                        $value
                    )
                )
            );
        }


        return implode(
            '&',
            $parts
        );
    }

    /**
     * @return \stdClass
     */
    public function getFields(): \stdClass
    {
        return $this->fields;
    }
}
