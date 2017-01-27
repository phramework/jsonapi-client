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
 * Error meta-class helpful for documentation and auto-complete
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 * @link http://jsonapi.org/format/#error-objects
 */
class Error
{
    /**
     * The HTTP status code applicable to this problem, expressed as a
     * string value
     * @var int
     */
    public $status;

    /**
     * A short, human-readable summary of the problem
     * @var string
     */
    public $title;

    /**
     * A human-readable explanation specific to this occurrence of the problem
     * @var string
     */
    public $detail;

    /**
     * A unique identifier for this particular occurrence of the problem
     * @var string
     */
    public $id;

    /**
     * An object containing references to the source of the error
     * **Note** usually it's not set
     * @var ErrorSource
     */
    public $source;

    /**
     * A links object
     * **Note** usually it's not set
     * @var \stdClass
     */
    public $links;

    /**
     * A meta object containing non-standard meta-information about the error
     * **Note** usually it's not set
     * @var \stdClass
     */
    public $meta;

    /**
     * An application-specific error code, expressed as a string value.
     * **Note** usually it's not set
     * @var string
     */
    public $code;
}
