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

use Phramework\JSONAPI\APP\DataSource\MemoryDataSource;
use Phramework\JSONAPI\Client\Client;
use Phramework\JSONAPI\Model;
use Phramework\JSONAPI\ModelTrait;
use Phramework\JSONAPI\Relationship;
use Phramework\JSONAPI\ResourceModel;
use Phramework\JSONAPI\ValidationModel;
use Phramework\Validate\ObjectValidator;
use Phramework\Validate\StringValidator;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 1.0
 */
class User extends Model
{
    use ModelTrait;

    protected static function defineModel() : ResourceModel
    {
        return (new ResourceModel('user'))
            ->addVariable('table', 'user')
            ->setSortableAttributes(
                'id'
            )->setFieldableAtributes(
                'name',
                'username'
            );
    }
}
