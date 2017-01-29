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
namespace Phramework\JSONAPI\Client\Exceptions;

use Phramework\JSONAPI\Client\Error;
use Phramework\JSONAPI\Client\Response\Errors;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class ResponseException extends \Exception
{
    /**
     * @var Errors
     */
    private $errors;

    /**
     * @param Errors $errors
     */
    public function __construct(Errors $errors)
    {
        $title = 'Response exception';

        if (isset(
            $errors->getErrors()[0],
            $errors->getErrors()[0]->title
        )) {
            $title = $errors->getErrors()[0]->title;
        }

        parent::__construct($title);

        $this->errors = $errors;
    }

    /**
     * @return Error[]
     */
    public function getErrors()
    {
        return $this->errors->getErrors();
    }
}
