<?php
declare(strict_types=1);
/**
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
namespace Phramework\JSONAPI\APP\Models;

use Phramework\Database\Database;
use Phramework\JSONAPI\APP\DataSource\MemoryDataSource;
use Phramework\JSONAPI\Client\Client;
use Phramework\JSONAPI\Client\Directive\Directive;
use Phramework\JSONAPI\Model;
use Phramework\JSONAPI\ModelTrait;
use Phramework\JSONAPI\Relationship;
use Phramework\JSONAPI\ResourceModel;
use Phramework\JSONAPI\ValidationModel;
use Phramework\Validate\ObjectValidator;
use Phramework\Validate\StringValidator;
use Phramework\Validate\UnsignedIntegerValidator;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 1.0
 */
class Tag extends Model
{
    use ModelTrait;

    protected static function defineModel() : ResourceModel
    {
        return (new ResourceModel('tag'))
            ->addVariable('table', 'tag')
            ->setIdAttributeValidator(
                new StringValidator(
                    1,
                    128,
                    '/^\d+$/'
                )
            )
            ->setSortableAttributes(
                'id'
            )
            ->setValidationModel(
                new ValidationModel(
                    new ObjectValidator(
                        (object) [
                            'title'  => new StringValidator(),
                            'status' => (new UnsignedIntegerValidator(0, 1))
                            ->setDefault(1)
                        ],
                        ['title'],
                        false
                    )
                ),
                'POST'
            )->setRelationships((object) [
                'article' => new Relationship(
                    function () {
                        return Article::getResourceModel();
                    },
                    Relationship::TYPE_TO_MANY,
                    null,
                    (object) [
                        'GET' => function (string $tagId) {
                            $ids = Database::executeAndFetchAllArray(
                                'SELECT "article-tag"."article_id"
                                FROM "article-tag"
                                JOIN "article"
                                 ON "article"."id" = "article-tag"."article_id"
                                WHERE "article-tag"."tag_id" = ?
                                  AND "article-tag"."status" <> 0
                                  AND "article"."status" <> 0',
                                [$tagId]
                            );
                            return $ids;
                        }
                    ]
                )
            ]);
    }
}
