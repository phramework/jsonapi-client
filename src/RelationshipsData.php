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
namespace Phramework\JSONAPI\Client;

/**
 * Relationships data, helper class, used to define relationship data in
 * POST, PATCH, PUT client requests
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 * @version 2.2.1
 */
class RelationshipsData
{
    /**
     * @var \stdClass
     */
    private $relationships;

    public function __construct()
    {
        $this->relationships = new \stdClass();
    }

    /**
     * Append relationships,
     * - if $relationship exist, values will be overwritten
     * - if array of ids will be used (for to-many relationships) it in normalized based on json api specification
     * @param string           $relationship
     * @param string|string[]  $id
     * @param string|null      $type Resource type. If null, `$relationship`
     *     will be used as type
     * @return $this
     * @example
     * ```
     * (new RelationshipsData())
     *     ->append('group', '29') //When $relationship and type are the same
     * ```
     * @example
     * ```php
     * (new RelationshipsData())
     *     ->append('friend', ['20', '30'], 'user') //When type is different
     * ```
     */
    public function append(
        string $relationship,
        $id,
        string $type = null
    ) {
        $type = $type ?? $relationship;

        if (is_array($id)) {
            $this->relationships->{$relationship} = (object) [
                'data' => array_map(
                    function (string $id) use ($type) {
                        return (object) [
                            'type' => $type,
                            'id'   => (string) $id
                        ];
                    },
                    $id
                )
            ];
        } else {
            $this->relationships->{$relationship} = (object) [
                'data' => (object) [
                    'type' => $type,
                    'id'   => (string) $id
                ]
            ];
        }

        return $this;
    }

    /**
     * Get relationships object
     * @return \stdClass
     */
    public function getRelationships() : \stdClass
    {
        return $this->relationships;
    }
}
