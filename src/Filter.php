<?php
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
namespace Phramework\JSONAPI\Client;

use Phramework\JSONAPI\FilterAttribute;
use Phramework\JSONAPI\FilterJSONAttribute;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class Filter extends \Phramework\JSONAPI\Filter
{

    /**
     * @param string $type
     * @return string
     */
    public function toURL(string $type): string
    {
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
}
