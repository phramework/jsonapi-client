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
namespace Phramework\JSONAPI\Client\Response;

use Phramework\JSONAPI\Client\Error;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 * @link http://jsonapi.org/format/#error-objects
 */
class Errors extends Response
{
    /**
     * @var Error[]
     */
    protected $errors;

    public function __construct(ResponseInterface $response)
    {
        $this->errors = [];

        parent::__construct($response);
    }

    /**
     * @return \Phramework\JSONAPI\Client\Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
